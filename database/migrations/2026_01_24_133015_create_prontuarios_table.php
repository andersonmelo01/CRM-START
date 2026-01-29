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
        Schema::create('prontuarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('consulta_id')
                ->constrained('consultas')
                ->cascadeOnDelete();

            $table->text('queixa_principal')->nullable();
            $table->text('historico_doenca')->nullable();
            $table->text('exame_fisico')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('conduta')->nullable();
            $table->text('prescricao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prontuarios');
    }
};
