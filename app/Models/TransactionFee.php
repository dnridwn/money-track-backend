<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionFee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'type',
        'operation',
        'format',
        'amount'
    ];

    public const TYPE_COMMISSION = 'Commission';
    public const TYPE_TAX = 'Tax';

    public const OPERATION_TYPE_INDUCT = 'Induct';
    public const OPERATION_TYPE_DEDUCT = 'Deduct';

    public const FORMAT_PERCENT = 'Percent';
    public const FORMAT_AMOUNT = 'Amount';

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
