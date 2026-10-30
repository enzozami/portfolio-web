<?php

require_once __DIR__ . '/../actions/ListProductAction.php';
require_once __DIR__ . '/../actions/CreateProductAction.php';
require_once __DIR__ . '/../actions/FindProductAction.php';
require_once __DIR__ . '/../actions/UpdateProductAction.php';
require_once __DIR__ . '/../actions/DeleteProductAction.php';

/**
 * Recebe os dados da requisição, valida, chama a action certa e devolve
 * para o index.php os dados que a view precisa. Não gera HTML.
 */
class ProductController
{
    // Código de erro do MySQL para valor repetido em coluna UNIQUE (a marca)
    private const DUPLICATE_ENTRY = 1062;

    public function __construct(
        private ListProductAction $listAction,
        private CreateProductAction $createAction,
        private FindProductAction $findAction,
        private UpdateProductAction $updateAction,
        private DeleteProductAction $deleteAction,
    ) {}

    public function index(array $params): array
    {
        // Tudo que vem da URL é string: o filter_var converte "1", "true", "on"... em booleano
        $sortBy = $params['sortBy'] ?? 'nome';
        $descending = filter_var($params['descending'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $active = filter_var($params['ativo'] ?? true, FILTER_VALIDATE_BOOLEAN);
        $list =  $this->listAction->handle(
            descending: $descending,
            sortBy: $sortBy,
            active: $active,
        );

        return ['products' => $list, 'sortBy' => $sortBy, 'descending' => $descending, 'active' => $active];
    }

    public function store(array $params): never
    {
        $errors = $this->validate($params, isEdit: false);

        if ($errors) {
            $this->backToForm('index.php?route=create', $errors, $params);
        }

        try {
            $this->createAction->handle(ProductDto::fromArray($params));
        } catch (PDOException $e) {
            $this->handleDuplicate($e, 'index.php?route=create', $params);
        }

        setFlash('success', 'Produto cadastrado com sucesso!');
        redirect('index.php');
    }

    public function show(int $id): array
    {
        return ['product' => $this->findAction->handle($id)];
    }

    public function update(int $id, array $input): never
    {
        $errors = $this->validate($input, isEdit: true);
        $formUrl = 'index.php?route=edit&id=' . $id;

        if ($errors) {
            $this->backToForm($formUrl, $errors, $input);
        }

        try {
            $this->updateAction->handle($id, ProductDto::fromArray($input));
        } catch (PDOException $e) {
            $this->handleDuplicate($e, $formUrl, $input);
        }

        setFlash('success', 'Produto atualizado com sucesso!');
        redirect('index.php');
    }

    public function destroy(int $id): never
    {
        $this->deleteAction->handle($id);
        setFlash('success', 'Produto excluído com sucesso!');
        redirect('index.php');
    }

    /**
     * Regras de validação do produto. As mesmas regras existem em
     * public/assets/js/form-validation.js, mas só esta é garantida:
     * o JavaScript pode ser desativado ou burlado.
     */
    private function validate(array $input, bool $isEdit): array
    {
        $errors = [];
        $nome = trim($input['nome'] ?? '');
        $marca = trim($input['marca'] ?? '');
        $senha = $input['senha'] ?? '';
        $cor = trim($input['cor'] ?? '');
        $valor = filter_var($input['valor'] ?? null, FILTER_VALIDATE_FLOAT);

        // mb_strlen conta caracteres; strlen contaria bytes ("ç" vale 2)
        if ($nome === '') {
            $errors['nome'] = 'O nome do produto é obrigatório.';
        } elseif (mb_strlen($nome) < 3) {
            $errors['nome'] = 'O nome do produto deve ter pelo menos 3 caracteres.';
        } elseif (mb_strlen($nome) > 100) {
            $errors['nome'] = 'O nome do produto deve ter no máximo 100 caracteres.';
        }

        if ($marca === '') {
            $errors['marca'] = 'A marca do produto é obrigatória.';
        } elseif (mb_strlen($marca) < 3) {
            $errors['marca'] = 'A marca do produto deve ter pelo menos 3 caracteres.';
        } elseif (mb_strlen($marca) > 100) {
            $errors['marca'] = 'A marca do produto deve ter no máximo 100 caracteres.';
        }

        // Na edição a senha é opcional: em branco mantém a atual
        if (!$isEdit && $senha === '') {
            $errors['senha'] = 'A senha do produto é obrigatória.';
        } elseif ($senha !== '' && mb_strlen($senha) < 8) {
            $errors['senha'] = 'A senha do produto deve ter pelo menos 8 caracteres.';
        }

        if ($cor === '') {
            $errors['cor'] = 'A cor do produto é obrigatória.';
        } elseif (mb_strlen($cor) > 50) {
            $errors['cor'] = 'A cor do produto deve ter no máximo 50 caracteres.';
        }

        // DECIMAL(10, 2) aceita no máximo 99.999.999,99
        if ($valor === false || $valor <= 0) {
            $errors['valor'] = 'O valor do produto é inválido.';
        } elseif ($valor > 99999999.99) {
            $errors['valor'] = 'O valor máximo é R$ 99.999.999,99.';
        }

        return $errors;
    }

    public function create(): array
    {
        $product = ['nome' => '', 'marca' => '',  'cor' => '', 'valor' => '', 'ativo' => 1];
        return ['product' => $this->withOldInput($product), 'isEdit' => false, 'errors' => getFormErrors()];
    }

    public function edit(int $id): array
    {
        $product = $this->findAction->handle($id);

        if ($product) {
            $product = $this->withOldInput($product);
        }

        return ['product' => $product, 'isEdit' => true, 'errors' => getFormErrors()];
    }

    /**
     * Volta ao formulário com os erros e o que o usuário digitou.
     */
    private function backToForm(string $url, array $errors, array $input): never
    {
        setFormErrors($errors);
        setOldInput($input);
        redirect($url);
    }

    /**
     * Transforma o erro de marca duplicada em erro de formulário. Qualquer
     * outro erro do banco é relançado, para não ser escondido.
     */
    private function handleDuplicate(PDOException $e, string $url, array $input): never
    {
        if (($e->errorInfo[1] ?? null) !== self::DUPLICATE_ENTRY) {
            throw $e;
        }

        $this->backToForm($url, ['marca' => 'Esta marca já está cadastrada.'], $input);
    }

    /**
     * Sobrescreve os dados do produto com o que o usuário digitou antes de um erro de validação.
     */
    private function withOldInput(array $product): array
    {
        $old = getOldInput();

        if (!$old) {
            return $product;
        }

        // O id vem do banco, nunca do formulário
        unset($old['id']);

        // Checkbox desmarcado não é enviado, então a ausência da chave significa "inativo"
        $old['ativo'] = !empty($old['ativo']) ? 1 : 0;

        return array_merge($product, $old);
    }
}
