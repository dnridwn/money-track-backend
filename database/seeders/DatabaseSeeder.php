<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionFee;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('local')) {
            $this->resetDatabase();
            $this->createDefaultData();
        }
    }

    public function resetDatabase(): void
    {
        Account::whereNotNull('id')->forceDelete();
        Category::whereNotNull('id')->forceDelete();
        Transaction::whereNotNull('id')->forceDelete();
        TransactionFee::whereNotNull('id')->forceDelete();
    }

    public function createDefaultData(): void
    {
        Account::create([
            'name' => 'Cash',
            'description' => '',
            'type' => 'Account',
            'balance' => 0
        ]);

        Category::create([
            'name' => 'Salary',
            'description' => '',
            'type' => 'Income'
        ]);

        Category::create([
            'name' => 'Food',
            'description' => '',
            'type' => 'Expense'
        ]);
    }
}
