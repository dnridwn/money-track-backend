<?php

namespace App\Services\Transaction;

use App\Models\Account;
use App\Models\Transaction;
use Error;

class BalanceAdjustmentService
{
    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function run()
    {
        $sourceAccount = $this->transaction->account;
        if (empty($sourceAccount)) {
            throw new Error('Source account not found');    
        }

        if ($this->transaction->type === Transaction::TYPE_TRANSFER) {
            $targetAccount = $this->transaction->transferToAccount;
            if (empty($targetAccount)) {
                throw new Error('Target account not found');    
            }
         
            $sourceAccount->balance -= $this->transaction->total_amount;
            $sourceAccount->save();

            $targetAccount->balance += $this->transaction->amount;
            $targetAccount->save();
        }

        if ($this->transaction->type === Transaction::TYPE_DEBIT) {
            $sourceAccount->balance += $this->transaction->amount;
            $sourceAccount->save();
        }

        if ($this->transaction->type === Transaction::TYPE_CREDIT) {
            $sourceAccount->balance -= $this->transaction->total_amount;
            $sourceAccount->save();
        }
    }
}
