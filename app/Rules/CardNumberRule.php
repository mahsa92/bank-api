<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CardNumberRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $convertedValue = $this->convertCardNumberToEnglish($value);
        if (empty($convertedValue) || strlen($convertedValue) !== 16) {
            $fail('The :attribute is not a valid bank card number.');
        }

        $cardToArr = str_split($convertedValue);
        $cardTotal = 0;
        for($i = 0; $i<16; $i++) {
            $c = (int)$cardToArr[$i];
            if($i % 2 === 0) {
                $cardTotal += (($c * 2 > 9) ? ($c * 2) - 9 : ($c * 2));
            } else {
                $cardTotal += $c;
            }
        }

        if (!($cardTotal % 10 === 0)) {
            $fail('The :attribute is not a valid bank card number.');
        }
    }

    private function convertCardNumberToEnglish(string $cardNumber): int
    {
        $persianDigits = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $arabicDigits = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');

        $convertedCardNumber = str_replace($persianDigits, range(0, 9), $cardNumber);

        return str_replace($arabicDigits, range(0, 9), $convertedCardNumber);
    }
}
