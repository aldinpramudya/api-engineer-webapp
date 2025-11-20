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
        Schema::create("masters_coa", function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger("category_coa_id");
            $table->integer("code");
            $table->string("name");
            $table->foreign("category_coa_id")->references("id")->on('categories_coa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop("masters_coa");
    }
};
