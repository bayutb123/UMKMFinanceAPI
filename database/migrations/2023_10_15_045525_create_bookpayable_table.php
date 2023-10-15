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
        Schema::create('book_payable', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('transaction_id');
            $table->date('transaction_date');
            $table->unsignedBigInteger('vendor_id');
            $table->integer('amount');
            $table->integer('paid')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookpayable');
    }
};
