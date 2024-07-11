<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_id',
        'worker_id',
        'ad_name',
        'full_name',
        'price',
        'address',
    ];
}
