<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArrivalProduct extends Model
{
    use HasFactory;

    protected $fillable = ['arrival_id', 'product_id', 'quantity', 'unit_price']; // Ajout de unit_price

    protected $casts = [
        'quantity' => 'integer', // Cast pour garantir que quantity est un entier
        'unit_price' => 'decimal:2', // Cast pour garantir que unit_price est un décimal avec 2 décimales
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function arrival()
    {
        return $this->belongsTo(Arrival::class, 'arrival_id');
    }
}
