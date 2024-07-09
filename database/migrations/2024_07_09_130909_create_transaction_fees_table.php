<?php

use App\Models\TransactionFee;
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
        Schema::create('transaction_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->nullable()->index();
            $table->enum('type', [TransactionFee::TYPE_TAX, TransactionFee::TYPE_COMMISSION])->nullable()->index();
            $table->enum('operation', [TransactionFee::OPERATION_TYPE_INDUCT, TransactionFee::OPERATION_TYPE_DEDUCT])->nullable()->index();
            $table->enum('format', [TransactionFee::FORMAT_PERCENT, TransactionFee::FORMAT_AMOUNT])->nullable()->index();
            $table->float('amount')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_fees');
    }
};
