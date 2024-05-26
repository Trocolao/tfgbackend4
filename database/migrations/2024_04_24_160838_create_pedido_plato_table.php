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
        Schema::create('pedido_plato', function (Blueprint $table) {
            $table->primary(['pedido_id','plato_id']);
            $table->bigInteger('pedido_id')->unsigned();
            $table->bigInteger('plato_id')->unsigned();
            $table->integer('cantidad');
            $table->timestamps();
            $table->foreign('pedido_id')
                ->references('id')
                ->on('pedidos');
            $table->foreign('plato_id')
                ->references('id')
                ->on('platos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_plato');
    }
};
