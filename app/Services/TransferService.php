<?php

namespace App\Services;

use App\Models\Card;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Services\Notifier\NotificationStrategyFactory;
use App\Services\Notifier\Notifier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;

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

        $this->notifyUsers($senderAccount->user, $receiveAccount->user, $amount);
    }

    private function notifyUsers(User $sender, User $receiver, int $amount): void
    {
        $strategy = NotificationStrategyFactory::create();
        $this->notifier->setStrategy($strategy);

        $this->notifier->notify($sender->mobile, $this->getSenderMessage($amount));
        $this->notifier->notify($receiver->mobile, $this->getReceiverMessage($amount));
    }

    private function getSenderMessage(int $amount): string
    {
        return Lang::get('transfer.sender_message', ['amount' => $amount, 'datetime' => (Carbon::now())->format('Y-m-d H:i:s')]);
    }


    private function getReceiverMessage(int $amount): string
    {
        return Lang::get('transfer.receiver_message', ['amount' => $amount, 'datetime' => (Carbon::now())->format('Y-m-d H:i:s')]);
    }
}
