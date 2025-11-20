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
        Schema::create("transactions", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("masters_coa_id");
            $table->timestamp("date");
            $table->foreign("masters_coa_id")->references("id")->on("masters_coa")->onDelete("cascade");
            $table->string("description");
            $table->decimal("debit");
            $table->decimal("credit");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop("transactions");
    }
};
