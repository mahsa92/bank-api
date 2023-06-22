<?php

namespace App\Services;

use App\Models\Card;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Services\Notifier\NotificationStrategyFactory;
use App\Services\Notifier\Notifier;

class TransferService
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly TransactionRepository $transactionRepository,
        private Notifier $notifier
    ){}
    public function __invoke(Card $senderCard, Card $receiverCard, int $amount): void
    {
        $senderAccount = $senderCard->account;
        $receiveAccount = $receiverCard->account;

        $this->accountRepository->deductMoney($senderAccount->id, $amount);
        $this->accountRepository->addMoney($receiveAccount->id, $amount);
        $this->transactionRepository->addTransaction($senderCard->id, $receiverCard->id, $amount);

        $this->notifyUsers($senderAccount->user, $receiveAccount->user);
    }

    private function notifyUsers(User $sender, User $receiver): void
    {
        $strategy = NotificationStrategyFactory::create();
        $this->notifier->setStrategy($strategy);

        $this->notifier->notify($sender->mobile, 'Hello, this is a test message for sender');
        $this->notifier->notify($receiver->mobile, 'Hello, this is a test message for receiver');
    }
}
