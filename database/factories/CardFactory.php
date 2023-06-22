<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bankCodes = ['603799', '589210', '627648', '627961', '603770', '628023', '627760'];
        $cardNumber = $this->faker->randomElement($bankCodes) . $this->faker->numerify('##########');
        return [
            'card_number' => $cardNumber,
            'account_id' => Account::factory(),
        ];
    }
}
