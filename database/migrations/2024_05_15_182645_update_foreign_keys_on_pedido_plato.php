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
        Schema::table('pedido_plato', function (Blueprint $table) {
            // Eliminar las claves foráneas existentes
            $table->dropForeign(['pedido_id']);
            $table->dropForeign(['plato_id']);

            // Volver a crear las claves foráneas con eliminación en cascada
            $table->foreign('pedido_id')
                ->references('id')
                ->on('pedidos')
                ->onDelete('cascade');

            $table->foreign('plato_id')
                ->references('id')
                ->on('platos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedido_plato', function (Blueprint $table) {
            // Revertir las claves foráneas a su estado original
            $table->dropForeign(['pedido_id']);
            $table->dropForeign(['plato_id']);

            $table->foreign('pedido_id')
                ->references('id')
                ->on('pedidos');

            $table->foreign('plato_id')
                ->references('id')
                ->on('platos');
        });
    }
};
