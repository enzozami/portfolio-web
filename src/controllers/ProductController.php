<?php

require_once __DIR__ . '/../actions/ListProductAction.php';
require_once __DIR__ . '/../actions/CreateProductAction.php';
require_once __DIR__ . '/../actions/FindProductAction.php';
require_once __DIR__ . '/../actions/UpdateProductAction.php';
require_once __DIR__ . '/../actions/DeleteProductAction.php';

class ProductController
{
    private const DUPLICATE_ENTRY = 1062;

    public function __construct(
        private ListProductAction $listAction,
        private CreateProductAction $createAction,
        private FindProductAction $findAction,
        private UpdateProductAction $updateAction,
        private DeleteProductAction $deleteAction,
    ) {}

    // Lista os produtos com filtro de status e ordenação
    public function index(array $params): array
    {
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

    // Valida e cadastra um novo produto
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

    // Busca um produto para a tela de detalhes
    public function show(int $id): array
    {
        return ['product' => $this->findAction->handle($id)];
    }

    // Valida e atualiza um produto existente
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

    // Exclui um produto
    public function destroy(int $id): never
    {
        $this->deleteAction->handle($id);
        setFlash('success', 'Produto excluído com sucesso!');
        redirect('index.php');
    }

    // Valida os campos do produto e retorna os erros encontrados
    private function validate(array $input, bool $isEdit): array
    {
        $errors = [];
        $nome = trim($input['nome'] ?? '');
        $marca = trim($input['marca'] ?? '');
        $senha = $input['senha'] ?? '';
        $cor = trim($input['cor'] ?? '');
        $valor = filter_var($input['valor'] ?? null, FILTER_VALIDATE_FLOAT);

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

        if ($valor === false || $valor <= 0) {
            $errors['valor'] = 'O valor do produto é inválido.';
        } elseif ($valor > 99999999.99) {
            $errors['valor'] = 'O valor máximo é R$ 99.999.999,99.';
        }

        return $errors;
    }

    // Prepara o formulário de cadastro
    public function create(): array
    {
        $product = ['nome' => '', 'marca' => '',  'cor' => '', 'valor' => '', 'ativo' => 1];
        return ['product' => $this->withOldInput($product), 'isEdit' => false, 'errors' => getFormErrors()];
    }

    // Prepara o formulário de edição com os dados do produto
    public function edit(int $id): array
    {
        $product = $this->findAction->handle($id);

        if ($product) {
            $product = $this->withOldInput($product);
        }

        return ['product' => $product, 'isEdit' => true, 'errors' => getFormErrors()];
    }

    // Volta ao formulário com os erros e o que o usuário digitou
    private function backToForm(string $url, array $errors, array $input): never
    {
        setFormErrors($errors);
        setOldInput($input);
        redirect($url);
    }

    // Trata o erro de marca duplicada como erro de formulário
    private function handleDuplicate(PDOException $e, string $url, array $input): never
    {
        if (($e->errorInfo[1] ?? null) !== self::DUPLICATE_ENTRY) {
            throw $e;
        }

        $this->backToForm($url, ['marca' => 'Esta marca já está cadastrada.'], $input);
    }

    // Preenche o produto com o que o usuário digitou antes do erro
    private function withOldInput(array $product): array
    {
        $old = getOldInput();

        if (!$old) {
            return $product;
        }

        unset($old['id']);

        $old['ativo'] = !empty($old['ativo']) ? 1 : 0;

        return array_merge($product, $old);
    }
}
