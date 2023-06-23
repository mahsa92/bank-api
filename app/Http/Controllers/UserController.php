<?php

namespace App\Http\Controllers;

use App\Repositories\{TransactionRepository, UserRepository};
use App\Http\Requests\MostTransactionRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private UserRepository $userRepository
    )
    {}

    public function mostTransactions(MostTransactionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $users = $this->userRepository->getActivestUsers($data['since']);
        $userIds = $users->pluck('id');
        $transactions = $this->transactionRepository->getLastTransactionsOfUsers($userIds);
        $userTransactions = [];

        foreach ($users as $user) {
            $userTransactions[$user->id] = [
                'user' => $user,
                'transactions' => [],
            ];
        }

        foreach ($transactions as $transaction) {
            $userId = $transaction->card->account->user_id;
            $userTransactions[$userId]['transactions'][] = $transaction;
        }

        return response()->json(array_values($userTransactions));
    }
}
