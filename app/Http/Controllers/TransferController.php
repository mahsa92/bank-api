<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Repositories\CardRepository;
use App\Services\TransferService;

class TransferController extends Controller
{

    public function __construct(private CardRepository $cardRepository)
    {}
    public function __invoke(TransferRequest $request, TransferService $transferService)
    {
        $validatedData = $request->validated();

        $senderCardNumber = $validatedData['sender_card_number'];
        $receiverCardNumber = $validatedData['receiver_card_number'];
        $amount = $validatedData['amount'];

        $senderCard = $this->cardRepository->findByCardNumber($senderCardNumber);
        $receiverCard = $this->cardRepository->findByCardNumber($receiverCardNumber);

        if (!$senderCard) {
            return response()->json(['error' => 'Invalid card number'], 400);
        }

        if ($senderCard->account->balance < $amount) {
            return response()->json(['error' => 'insufficient balance'], 400);
        }

        $transferService($senderCard, $receiverCard, $amount);

        return response()->json(['message' => 'Money transferred successfully']);
    }
}
