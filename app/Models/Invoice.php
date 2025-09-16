<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'product_id',
        'tripay_reference',
        'buyer_email',
        'buyer_phone',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
