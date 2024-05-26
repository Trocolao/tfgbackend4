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
        Schema::table('evento_user', function (Blueprint $table) {
            // Primero elimina las claves foráneas existentes
            $table->dropForeign(['user_id']);
            $table->dropForeign(['evento_id']);

            // Luego, añade las claves foráneas con onDelete('cascade')
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('evento_id')
                ->references('id')
                ->on('eventos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evento_user', function (Blueprint $table) {
            // Elimina las claves foráneas con onDelete('cascade')
            $table->dropForeign(['user_id']);
            $table->dropForeign(['evento_id']);

            // Restaura las claves foráneas originales sin onDelete('cascade')
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('evento_id')
                ->references('id')
                ->on('eventos');
        });
    }
};
