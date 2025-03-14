<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArrivalProduct extends Model
{
    use HasFactory;

    protected $fillable = ['arrival_id', 'product_id', 'quantity'];

    public function arrival()
    {
        return $this->belongsTo(Arrival::class);
    }
}
