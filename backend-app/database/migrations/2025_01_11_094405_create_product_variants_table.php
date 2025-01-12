<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('product_id');
            $table->integer('bigcommerce_id');
            $table->string('sku');
            $table->decimal('price', 10, 2)->nullable();
            $table->json('option_values');
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->index('product_id');
            $table->index('bigcommerce_id');
            $table->index('sku');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};
