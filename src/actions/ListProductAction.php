<?php

require_once __DIR__ . '/../src/database/connection.php';

class ListProductAction
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = connection();
    }


    public function handle(String $direction, String $sortBy,): array
    {
        $sql = "SELECT * FROM produtos ORDER BY :sortBy :direction";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':sortBy' => $sortBy, ':direction' => $direction]);
        return $stmt->fetchAll();
    }
}