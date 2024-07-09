<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'account_id',
        'category_id',
        'transfer_to_account_id',
        'date',
        'amount',
        'total_amount',
        'note'
    ];

    public const TYPE_CREDIT = 'Credit';
    public const TYPE_DEBIT = 'Debit';
    public const TYPE_TRANSFER = 'Transfer';

    protected $appends = [
        'date_formatted',
        'total_amount_formatted'
    ];

    public function getDateFormattedAttribute()
    {
        return !empty($this->date) ? Carbon::createFromFormat('Y-m-d', $this->date)->format('d/m/Y') : 'N/A';
    }

    public function getAmountFormattedAttribute()
    {
        return number_format($this->total_amount);
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function transferToAccount()
    {
        return $this->belongsTo(Account::class, 'transfer_to_account_id');
    }

    public function fees()
    {
        return $this->hasMany(TransactionFee::class, 'transaction_id');
    }
}
