<?php

require_once __DIR__ . '/../actions/ListProductAction.php';
require_once __DIR__ . '/../actions/CreateProductAction.php';
require_once __DIR__ . '/../actions/FindProductAction.php';
require_once __DIR__ . '/../actions/UpdateProductAction.php';
require_once __DIR__ . '/../actions/DeleteProductAction.php';

class ProductController
{
    public function __construct(
        private ListProductAction $listAction,
        private CreateProductAction $createAction,
        private FindProductAction $findAction,
        private UpdateProductAction $updateAction,
        private DeleteProductAction $deleteAction,
    ) {}

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

    public function store(array $params): never
    {
        $errors = $this->validate($params, isEdit: false);

        if ($errors) {
            setFormErrors($errors);
            // redirect('index.php?route=create');
        }

        $this->createAction->handle(ProductDto::fromArray($params));
        redirect('index.php');
    }

    public function show(int $id): array
    {
        return ['product' => $this->findAction->handle($id)];
    }

    public function update(int $id, array $input): never
    {
        $errors = $this->validate($input, isEdit: true);

        if ($errors) {
            setFormErrors($errors);
            // redirect('index.php?route=edit&id=' . $id);
        }

        $this->updateAction->handle($id, ProductDto::fromArray($input));
        redirect('index.php');
    }

    public function destroy(int $id): never
    {
        $this->deleteAction->handle($id);
        redirect('index.php');
    }

    private function validate(array $input, bool $isEdit): array
    {
        $errors = [];
        $nome = trim($input['nome'] ?? '');
        $marca = trim($input['marca'] ?? '');
        $senha = $input['senha'] ?? '';
        $cor = trim($input['cor'] ?? '');
        $valor = filter_var($input['valor'] ?? null, FILTER_VALIDATE_FLOAT);

        if (empty($nome)) {
            $errors['nome'] = 'O nome do produto é obrigatório.';
        } else if (strlen($nome) < 3) {
            $errors['nome'] = 'O nome do produto deve ter pelo menos 3 caracteres.';
        }

        if (empty($marca)) {
            $errors['marca'] = 'A marca do produto é obrigatória.';
        } else if (strlen($marca) < 3) {
            $errors['marca'] = 'A marca do produto deve ter pelo menos 3 caracteres.';
        }

        if (!$isEdit && $senha === '') {
            $errors['senha'] = 'A senha do produto é obrigatória.';
        } elseif ($senha !== '' && strlen($senha) < 8) {
            $errors['senha'] = 'A senha do produto deve ter pelo menos 8 caracteres.';
        }

        if (empty($cor)) {
            $errors['cor'] = 'A cor do produto é obrigatória.';
        }

        if ($valor === false || $valor <= 0) {
            $errors['valor'] = 'O valor do produto é inválido.';
        }

        return $errors;
    }

    public function create(): array
    {
        $product = ['nome' => '', 'marca' => '',  'cor' => '', 'valor' => '', 'ativo' => 1];
        return ['product' => $product, 'isEdit' => false];
    }

    public function edit(int $id): array
    {
        return ['product' => $this->findAction->handle($id), 'isEdit' => true];
    }
}
