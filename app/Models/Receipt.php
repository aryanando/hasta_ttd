<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Receipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'hospital_name',
        'received_from',
        'amount',
        'amount_in_words',
        'payment_purpose',
        'recipient',
        'public_code',
        'image_path',
        'status',
        'signature',
    ];

    // Automatically generate a public code when creating a new receipt
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($receipt) {
            $receipt->public_code = Str::random(32); // Generate unique 32-character string
        });
    }
}
