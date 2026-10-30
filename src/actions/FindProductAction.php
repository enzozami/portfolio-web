<?php

/**
 * Busca um produto pelo id.
 */
class FindProductAction
{
    public function __construct(private PDO $pdo) {}

    /**
     * @return array|false O produto, ou false se o id não existe
     */
    public function handle(int $id): array|false
    {
        $sql = "SELECT * FROM produtos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
