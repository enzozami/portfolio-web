<?php

require_once __DIR__ . '/../database/connection.php';

class ListProductAction
{
    public function __construct(private PDO $pdo)
    {
        $this->pdo = connection();
    }

    private const array SORTABLE = ['id', 'nome', 'marca', 'cor', 'valor', 'ativo'];



    public function handle(bool $descending, string $sortBy, bool $active): array
    {
        $sortBy = in_array($sortBy, self::SORTABLE) ? $sortBy : 'nome';
        $direction = $descending ? 'DESC' : 'ASC';

        $sql = "SELECT * FROM produtos ORDER BY :sortBy :direction WHERE ativo = :active";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':sortBy' => $sortBy, ':direction' => $direction, ':active' => $active]);
        return $stmt->fetchAll();
    }
}