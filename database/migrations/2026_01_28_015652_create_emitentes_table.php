<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('emitentes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('documento', 20); // CPF ou CNPJ
            $table->string('telefone')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('mensagem_rodape')->nullable();
            $table->boolean('ativo')->default(true);
            $table->date('validade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emitentes');
    }
};
