<?php

require_once __DIR__ . '/../src/database/connection.php';
require_once __DIR__ . '/../src/dto/ProductDto.php';

class CreateProductAction
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = connection();
    }

    public function handle(ProductDto $dto): void
    {
        $sql = 'INSERT INTO produtos (nome, marca, senha, cor, valor, ativo)
             VALUES (:nome, :marca, :senha, :cor, :valor, :ativo)';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($dto->toArray());
    }
}