<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_INCOME = 'Income';
    public const TYPE_EXPENSE = 'Expense';

    protected $fillable = [
        'name',
        'description',
        'type'
    ];

    public function getTotalTransactionAmountAttribute()
    {
        return $this->transactions()->sum('amount');
    }

    public function getTotalTransactionAmountFormattedAttribute()
    {
        return number_format($this->total_transaction_amount, 2);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }
}
