<?php

class Product
{
    public function __construct(
        private readonly int $id,
        private readonly string $nome,
        private readonly string $marca,
        private readonly string $senha,
        private readonly DateTimeImmutable $createdAt,
        private readonly string $cor,
        private readonly float $valor,
        private readonly bool $ativo,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getNome(): string
    {
        return $this->nome;
    }
    public function getMarca(): string
    {
        return $this->marca;
    }
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getCor(): string
    {
        return $this->cor;
    }
    public function getValor(): float
    {
        return $this->valor;
    }
    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function verificarSenha(string $senha): bool
    {
        return password_verify($senha, $this->senha);
    }
}