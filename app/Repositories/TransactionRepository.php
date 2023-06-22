<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Collection;

class TransactionRepository extends Repository
{
    const TRANSACTION_COMMISSION = 500;

    protected array $fillable = [
        'sender_card_id',
        'receiver_card_id',
        'fee',
        'commission'
    ];
    public function model(): string
    {
        return Transaction::class;
    }

    public function addTransaction(int $senderCardId, int $receiverCardId, int $amount)
    {
        $this->create([
            'sender_card_id' => $senderCardId,
            'receiver_card_id' => $receiverCardId,
            'fee' => $amount,
            'commission' => self::TRANSACTION_COMMISSION
        ], $this->fillable);
    }

    public function getLastTransactionsOfUsers(Collection $userIds): Collection
    {
        return $this->query()->whereIn('sender_card_id', function ($query) use ($userIds) {
            $query->select('id')
                ->from('cards')
                ->whereIn('account_id', function ($query) use ($userIds) {
                    $query->select('id')
                        ->from('accounts')
                        ->whereIn('user_id', $userIds);
                });
        })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }
}
