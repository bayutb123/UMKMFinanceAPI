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
        Schema::table("users", function(Blueprint $table) {
            $table->string("address")->nullable();
            $table->string("owner_name")->nullable();
            $table->string("business_sector")->nullable();
            $table->string("phone")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function(Blueprint $table) {
            $table->dropColumn("address");
            $table->dropColumn("owner_name");
            $table->dropColumn("business_sector");
            $table->dropColumn("phone");
        });
    }
};
