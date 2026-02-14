@extends('layouts.app')

@section('titulo', 'Bloqueio de Horários')

@section('conteudo')
<div class="container py-4">

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">🚫 Bloqueio de Horários</h3>
        <a href="{{ route('bloqueios.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Voltar
        </a>
    </div>

    {{-- Card do formulário --}}
    <div class="card shadow-sm rounded-4">
        <div class="card-body">

            <form method="POST" action="{{ route('bloqueios.store') }}">
                @csrf

                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">Data <span class="text-danger">*</span></label>
                        <input type="date" name="data" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Hora Início</label>
                        <input type="time" name="hora_inicio" class="form-control">
                        <small class="text-muted d-block">Deixe vazio para bloquear o dia inteiro</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Hora Fim</label>
                        <input type="time" name="hora_fim" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Médico (opcional)</label>
                        <select name="medico_id" class="form-select">
                            <option value="">Todos</option>
                            @foreach($medicos as $m)
                            <option value="{{ $m->id }}">{{ $m->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="mt-3">
                    <label class="form-label">Motivo</label>
                    <textarea name="motivo" class="form-control" rows="3" placeholder="Ex: Manutenção, férias, reunião"></textarea>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-lock-fill me-1"></i> Bloquear
                    </button>

                    <a href="{{ route('bloqueios.bloqueados') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle me-1"></i> Voltar
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection