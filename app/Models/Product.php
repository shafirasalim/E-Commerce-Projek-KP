<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable=[
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'status'
    ];

    protected $casts=[
        'price' => 'decimal:2'
    ];

    public function cetegory() : BelongsTo
    {
        return $this-> belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
