<?php

require_once __DIR__ . '/../src/database/connection.php';
require_once __DIR__ . '/../src/dto/ProductDto.php';

class UpdateProductAction
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = connection();
    }

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