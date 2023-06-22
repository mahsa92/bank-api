<?php

namespace App\Http\Requests;

use App\Rules\CardNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransferRequest extends FormRequest
{
    const MIN=1000;
    const MAX=50_000_000;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sender_card_number' => [
                'required',
                'string',
                'different:receiver_card_number',
                'size:16',
                new CardNumberRule(),
            ],
            'receiver_card_number' => [
                'required',
                'string',
                'size:16',
                new CardNumberRule(),
            ],
            'amount' => "required|numeric|min:".self::MIN."|max:".self::MAX,
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors(),
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
