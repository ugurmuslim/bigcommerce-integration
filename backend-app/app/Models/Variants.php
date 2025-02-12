<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variants extends Model
{
    use  HasFactory;

    protected $table = 'product_variants';
    protected $fillable = [
        'name',
        'price',
        'sku',
        'user_id',
        'bigcommerce_id',
        'option_values',
    ];

    protected $casts = [
        'option_values' => 'array',
    ];
}
