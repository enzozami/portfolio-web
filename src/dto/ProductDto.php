<?php

class ProductDto
{
    public function __construct(
        private readonly string $nome,
        private readonly string $marca,
        private readonly string $senha,
        private readonly string $cor,
        private readonly float $valor,
        private readonly bool $ativo,

    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nome: trim($data['nome']->toString()),
            marca: trim($data['marca']->toString()),
            senha: $data['senha'] ?? '',
            cor: trim($data['cor']->toString()),
            valor: (float) str_replace(',', '.', (string) ($data['valor'] ?? 0)),
            ativo: !empty($data['ativo']),
        );
    }

    public function toArray(): array
    {
        $data = [
            'nome' => $this->nome,
            'marca' => $this->marca,
            'cor' => $this->cor,
            'valor' => $this->valor,
            'ativo' => $this->ativo,
        ];

        if (!empty($this->senha)) {
            $data['senha'] = password_hash($this->senha, PASSWORD_DEFAULT);
        }

        return $data;
    }
}