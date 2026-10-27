<?php

require_once __DIR__ . '/../database/connection.php';

class DeleteProductAction
{
    public function __construct(private PDO $pdo)
    {
        $this->pdo = connection();
    }

    public function handle(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}