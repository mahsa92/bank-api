<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_card_id',
        'receiver_card_id',
        'fee',
        'commission'
    ];
    public function card()
    {
        return $this->belongsTo(Card::class, 'sender_card_id');
    }
}
