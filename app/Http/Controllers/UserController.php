<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function mostTransactions()
    {
        $currentTime = Carbon::now();
        $tenMinutesAgo = $currentTime->subMinutes(10);
        $userIds = [];

        $users = DB::table('users')
            ->join('accounts', 'users.id', '=', 'accounts.user_id')
            ->join('cards', 'accounts.id', '=', 'cards.account_id')
            ->join('transactions', 'cards.id', '=', 'transactions.sender_card_id')
            ->select('users.id', 'users.name', DB::raw('COUNT(transactions.id) as transaction_count'))
            ->where('transactions.created_at', '>=', $tenMinutesAgo)
            ->groupBy('users.id', 'users.name')
            ->orderBy('transaction_count', 'desc')
            ->limit(3)
            ->get();

        foreach ($users as $user) {
            $userIds[] = $user->id;
        }

        $transactions = DB::table('transactions')
            ->join('cards', 'transactions.sender_card_id', '=', 'cards.id')
            ->join('accounts', 'cards.account_id', '=', 'accounts.id')
            ->whereIn('accounts.user_id', $userIds)
            ->select('transactions.*', 'accounts.user_id')
            ->orderBy('transactions.created_at', 'desc')
            ->limit(10)
            ->get();

        $userTransactions = [];

        foreach ($users as $user) {
            $userTransactions[$user->id] = [
                'user' => $user,
                'transactions' => [],
            ];
        }

        foreach ($transactions as $transaction) {
            $userId = $transaction->user_id;
            $userTransactions[$userId]['transactions'][] = $transaction;
        }

        return response()->json(array_values($userTransactions));
    }
}
