<?php

use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [Transaction::TYPE_CREDIT, Transaction::TYPE_DEBIT, Transaction::TYPE_TRANSFER])->nullable()->index();
            $table->unsignedBigInteger('account_id')->index()->nullable();
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->unsignedBigInteger('transfer_to_account_id')->nullable()->index();
            $table->date('date')->nullable()->index();
            $table->float('amount')->nullable();
            $table->float('total_amount')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
