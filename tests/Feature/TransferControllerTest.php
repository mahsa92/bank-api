<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\{Account, Card, User};
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransferControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_transfer_money_successfully()
    {
        // Arrange
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $senderAccount = Account::factory()->create(['user_id' => $sender->id]);
        $receiverAccount = Account::factory()->create(['user_id' => $receiver->id]);

        $senderCard = Card::factory()->create([
            'account_id' => $senderAccount->id,
            'card_number' => '6362141108012700'
        ]);
        $receiverCard = Card::factory()->create([
            'account_id' => $receiverAccount->id,
            'card_number' => '6362141111660974'
        ]);
        $amount = 1000;

        $transferData = [
            'sender_card_number' => $senderCard->card_number,
            'receiver_card_number' => $receiverCard->card_number,
            'amount' => $amount,
        ];

        // Act
        $response = $this->postJson('/api/transfer', $transferData);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Money transferred successfully',
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $senderAccount->id,
            'balance' => $senderAccount->balance - $amount,
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $receiverAccount->id,
            'balance' => $receiverAccount->balance + $amount,
        ]);

        $this->assertDatabaseHas('transactions', [
            'sender_card_id' => $senderCard->id,
            'receiver_card_id' => $receiverCard->id,
            'fee' => $amount,
        ]);
    }
    public function test_transfer_money_get_error_when_card_is_not_valid()
    {
        // Arrange
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $senderAccount = Account::factory()->create(['user_id' => $sender->id]);
        $receiverAccount = Account::factory()->create(['user_id' => $receiver->id]);

        $senderCard = Card::factory()->create([
            'account_id' => $senderAccount->id,
            'card_number' => '1234123412341234'
        ]);
        $receiverCard = Card::factory()->create([
            'account_id' => $receiverAccount->id,
            'card_number' => '6362141111660974'
        ]);
        $amount = 1000;

        $transferData = [
            'sender_card_number' => $senderCard->card_number,
            'receiver_card_number' => $receiverCard->card_number,
            'amount' => $amount,
        ];

        // Act
        $response = $this->postJson('/api/transfer', $transferData);

        // Assert
        $response->assertStatus(422);
        $response->assertJson([
            "errors" => [
                "sender_card_number" => [
                    "The sender card number is not a valid bank card number."
                ]
            ]
        ]);
    }
    public function test_transfer_money_get_error_when_sender_and_receiver_are_the_same()
    {
        // Arrange
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $senderAccount = Account::factory()->create(['user_id' => $sender->id]);
        $receiverAccount = Account::factory()->create(['user_id' => $receiver->id]);

        $senderCard = Card::factory()->create([
            'account_id' => $senderAccount->id,
            'card_number' => '6362141111660974'
        ]);
        $receiverCard = Card::factory()->create([
            'account_id' => $receiverAccount->id,
            'card_number' => '6362141111660974'
        ]);
        $amount = 1000;

        $transferData = [
            'sender_card_number' => $senderCard->card_number,
            'receiver_card_number' => $receiverCard->card_number,
            'amount' => $amount,
        ];

        // Act
        $response = $this->postJson('/api/transfer', $transferData);

        // Assert
        $response->assertStatus(422);
        $response->assertJson([
            "errors" => [
                "sender_card_number" => [
                    "The sender card number field and receiver card number must be different."
                ]
            ]
        ]);
    }
}

