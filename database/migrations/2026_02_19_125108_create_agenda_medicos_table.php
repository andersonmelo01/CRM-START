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
        Schema::create('agenda_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medico_id')->constrained()->cascadeOnDelete();
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->integer('intervalo')->default(30); // minutos entre consultas
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_medicos');
    }
};
