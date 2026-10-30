<?php

require_once __DIR__ . '/../dto/ProductDto.php';

/**
 * Insere um novo produto. Lança PDOException (código 1062) se a marca já existir.
 */
class CreateProductAction
{
    public function __construct(private PDO $pdo) {}

    public function handle(ProductDto $dto): void
    {
        $sql = 'INSERT INTO produtos (nome, marca, senha, cor, valor, ativo)
             VALUES (:nome, :marca, :senha, :cor, :valor, :ativo)';

        // Prepared statement: os valores vão separados do SQL, o que impede SQL Injection
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($dto->toArray());
    }
}
