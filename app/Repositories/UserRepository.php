<?php

namespace App\Repositories;

use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserRepository extends Repository
{
    public function model(): string
    {
        return User::class;
    }

    public function get(int $cardNumber): ?Card
    {
        return $this->query()->where('card_number', $cardNumber)->first();
    }

    public function getActivestUsers($since): Collection
    {
        return $this->query()
            ->join('accounts', 'users.id', '=', 'accounts.user_id')
            ->join('cards', 'accounts.id', '=', 'cards.account_id')
            ->join('transactions', 'cards.id', '=', 'transactions.sender_card_id')
            ->select('users.id', 'users.name', DB::raw('COUNT(transactions.id) as transaction_count'))
            ->where('transactions.created_at', '>=', $since)
            ->groupBy('users.id', 'users.name')
            ->orderBy('transaction_count', 'desc')
            ->limit(3)
            ->get();
    }
}
