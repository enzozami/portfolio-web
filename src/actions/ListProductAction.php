<?php


class ListProductAction
{
    public function __construct(private PDO $pdo) {}

    private const SORTABLE = ['id', 'nome', 'marca', 'cor', 'valor', 'ativo'];

    public function handle(bool $descending, string $sortBy, int $active): array
    {
        $sortBy = in_array($sortBy, self::SORTABLE) ? $sortBy : 'nome';
        $direction = $descending ? 'DESC' : 'ASC';

        $sql = "SELECT * FROM produtos WHERE ativo = :active ORDER BY {$sortBy} {$direction}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':active' => $active]);
        return $stmt->fetchAll();
    }
}
