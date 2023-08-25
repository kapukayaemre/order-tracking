<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_id",
        "discount_rate",
        "status"
    ];

    public function product():HasMany
    {
        return $this->hasMany(Product::class);
    }
}
