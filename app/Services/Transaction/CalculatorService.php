<?php

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Models\TransactionCommission;
use App\Models\TransactionFee;
use App\Models\TransactionTax;

class CalculatorService
{
    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function calculateTotalAmount(): float
    {
        $totalAmount = $this->transaction->amount ?? 0;

        foreach (($this->transaction->fees ?? []) as $fee) {
            if ($fee->type === TransactionFee::OPERATION_TYPE_INDUCT) {
                if ($fee->format === TransactionFee::FORMAT_AMOUNT) {
                    $totalAmount += $fee->amount;
                } else if ($fee->format === TransactionFee::FORMAT_PERCENT) {
                    $totalAmount += ($totalAmount * ($fee->amount / 100));
                }
            } else if ($fee->type === TransactionFee::OPERATION_TYPE_DEDUCT) {
                if ($fee->format === TransactionFee::FORMAT_AMOUNT) {
                    $totalAmount -= $fee->amount;
                } else if ($fee->format === TransactionFee::FORMAT_PERCENT) {
                    $totalAmount -= ($totalAmount * ($fee->amount / 100));
                }
            }
        }

        return $totalAmount;
    }
}
