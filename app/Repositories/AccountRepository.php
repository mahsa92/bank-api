<?php

namespace App\Repositories;

use App\Models\Account;

class AccountRepository extends Repository
{
    protected array $fillable = [
        'account_number',
        'balance',
        'user_id'
    ];

    public function model(): string
    {
        return Account::class;
    }

    public function deductMoney(int $id, int $amount): void
    {
        $this->query()->whereKey($id)->decrement('balance', $amount);
    }
    public function addMoney(mixed $id, int $amount): void
    {
        $this->query()->whereKey($id)->increment('balance', $amount);
    }
}
