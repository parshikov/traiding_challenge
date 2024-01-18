<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed price
 * @property mixed name
 * @property mixed created_at
 */
class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
    ];

    protected $visible = [
        'name',
        'price',
    ];
}
