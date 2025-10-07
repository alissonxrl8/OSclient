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
        Schema::create('ordems', function (Blueprint $table) {
            $table->id();
            $table->string('obs');
            $table->string('modelo');
            $table->string('servico');
            $table->string('descricao');
            $table->decimal('dias_garantia');
            $table->integer('id_servico');
            $table->decimal('preco', 10, 2);
            $table->decimal('preco_pago', 10, 2);
            $table->integer('id_user');
            $table->integer('id_cliente');
            $table->date('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordems');
    }
};
