<?php

class FindProductAction
{
    public function __construct(private PDO $pdo) {}

    public function handle(int $id)
    {
        $sql = "SELECT * FROM produtos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
