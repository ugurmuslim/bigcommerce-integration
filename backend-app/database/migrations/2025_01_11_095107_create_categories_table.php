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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->integer('bigcommerce_id');
            $table->integer('parent_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('bigcommerce_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
