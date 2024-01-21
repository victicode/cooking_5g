<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_x_out_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('out_order_id');
            $table->foreign('out_order_id')->references('id')->on('out_orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->unsignedBigInteger('lote_id');
            $table->foreign('lote_id')->references('id_lote')->on('lotes')->onDelete('cascade');
            $table->timestamps();
     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_x_out_orde');
    }
};
