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
        Schema::create('pedidos_exames', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consulta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('prontuario_id')->nullable()->constrained()->cascadeOnDelete();

            $table->string('tipo_exame');
            $table->text('descricao')->nullable();
            $table->date('data_solicitacao');

            $table->string('status')->default('solicitado');
            // solicitado | realizado | entregue

            $table->text('resultado')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos_exames');
    }
};
