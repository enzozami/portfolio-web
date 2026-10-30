<?php

require_once __DIR__ . '/../dto/ProductDto.php';

/**
 * Atualiza um produto. Lança PDOException (código 1062) se a nova marca já existir.
 */
class UpdateProductAction
{
    public function __construct(private PDO $pdo) {}

    public function handle(int $id, ProductDto $dto): void
    {
        $data = $dto->toArray();
        $data['id'] = $id;

        $sql = 'UPDATE produtos SET nome = :nome, marca = :marca, cor = :cor, valor = :valor, ativo = :ativo';

        // A senha só é alterada se uma nova foi digitada (o DTO omite a chave quando vem vazia)
        if (isset($data['senha'])) {
            $sql .= ', senha = :senha';
        }

        $sql .= ' WHERE id = :id';

        $this->pdo->prepare($sql)->execute($data);
    }
}
