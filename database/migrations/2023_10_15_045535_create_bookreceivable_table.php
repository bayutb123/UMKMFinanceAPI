<?php

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
        Schema::create('book_receivable', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('transaction_id');
            $table->date('transaction_date');
            $table->unsignedBigInteger('customer_id');
            $table->integer('amount');
            $table->integer('paid_amount')->default(0);
            $table->integer('paid')->default(0);
            $table->date('paid_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_receivable');
    }
};
