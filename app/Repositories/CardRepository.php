<?php

namespace App\Repositories;

use App\Models\Card;

class CardRepository extends Repository
{
    public function model(): string
    {
        return Card::class;
    }

    public function findByCardNumber(int $cardNumber): ?Card
    {
        return $this->query()->where('card_number', $cardNumber)->first();
    }
}
