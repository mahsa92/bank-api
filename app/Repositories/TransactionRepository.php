<?php

namespace App\Repositories;

use App\Models\Transaction;

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
}
