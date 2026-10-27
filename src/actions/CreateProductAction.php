<?php

require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../dto/ProductDto.php';

class CreateProductAction
{
    public function __construct(private PDO $pdo) {}

    public function handle(ProductDto $dto): void
    {
        $sql = 'INSERT INTO produtos (nome, marca, senha, cor, valor, ativo)
             VALUES (:nome, :marca, :senha, :cor, :valor, :ativo)';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($dto->toArray());
    }
}