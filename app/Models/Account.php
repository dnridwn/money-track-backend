<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_ACCOUNT = 'Account';
    public const TYPE_DEBT = 'Debt';

    protected $fillable = [
        'name',
        'description',
        'type',
        'balance'
    ];

    protected $appends = [
        'balance_formatted'
    ];

    public function getBalanceFormattedAttribute()
    {
        return number_format($this->balance);
    }
}
