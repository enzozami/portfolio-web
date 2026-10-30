<?php

require_once __DIR__ . '/../dto/ProductDto.php';

class UpdateProductAction
{
    public function __construct(private PDO $pdo) {}

    public function handle(int $id, ProductDto $dto)
    {
        $data = $dto->toArray();
        $data['id'] = $id;

        $sql = 'UPDATE produtos SET nome = :nome, marca = :marca, cor = :cor, valor = :valor, ativo = :ativo';

        if (isset($data['senha'])) {
            $sql .= ', senha = :senha';
        }

        $sql .= ' WHERE id = :id';

        $this->pdo->prepare($sql)->execute($data);
    }
}
