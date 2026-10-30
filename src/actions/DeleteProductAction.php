<?php

/**
 * Exclui um produto definitivamente.
 */
class DeleteProductAction
{
    public function __construct(private PDO $pdo) {}

    public function handle(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
