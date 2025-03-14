<?php

namespace App\Models;

use App\Models\ArrivalProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arrival extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'status'];

    public function products()
    {
        return $this->hasMany(ArrivalProduct::class);
    }
}
