<?php

require_once __DIR__ . '/../src/database/connection.php';

class DeleteProductAction
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = connection();
    }

    public function handle(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}