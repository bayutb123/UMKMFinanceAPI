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
        Schema::create('book_inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->foreignId('transaction_id')->constrained();
            $table->date('date');
            $table->foreignId('product_id')->constrained();
            $table->integer('in')->default(0);
            $table->integer('purchased_in_price')->default(0);
            $table->integer('out')->default(0); 
            $table->integer('sold_in_price')->default(0);
            $table->integer('qty')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_inventory');
    }
};
