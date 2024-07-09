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
}
