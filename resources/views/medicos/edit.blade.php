@extends('layouts.app')

@section('titulo', 'Editar Médico')

@section('conteudo')

<div class="card">
    <div class="card-header bg-dark text-white">Editar Médico</div>

    <div class="card-body">
        <form method="POST" action="{{ route('medicos.update', $medico) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nome</label>
                <input name="nome" class="form-control" value="{{ $medico->nome }}" required>
            </div>

            <div class="mb-3">
                <label>CRM</label>
                <input name="crm" class="form-control" value="{{ $medico->crm }}" required>
            </div>

            <div class="mb-3">
                <label>Especialidade</label>
                <input name="especialidade" class="form-control" value="{{ $medico->especialidade }}" required>
            </div>

            <div class="mb-3">
                <label>Telefone</label>
                <input name="telefone" class="form-control" value="{{ $medico->telefone }}">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input name="email" class="form-control" value="{{ $medico->email }}">
            </div>
            <!-- ⬇️ AQUI entra o bloqueio -->
            <hr>

            <div class="form-check mt-3">
                <input type="checkbox"
                    class="form-check-input"
                    name="criar_bloqueio"
                    id="criar_bloqueio">

                <label class="form-check-label" for="criar_bloqueio">
                    Médico começa bloqueado
                </label>
            </div>

            <div id="dadosBloqueio" style="display:none">
                <label class="mt-2">Data do bloqueio</label>
                <input type="date" name="bloqueio_data" class="form-control">

                <label class="mt-2">Hora início</label>
                <input type="time" name="bloqueio_inicio" class="form-control">

                <label class="mt-2">Hora fim</label>
                <input type="time" name="bloqueio_fim" class="form-control">

                <label class="mt-2">Motivo</label>
                <textarea name="bloqueio_motivo" class="form-control"></textarea>
            </div>

            <button class="btn btn-success">Atualizar</button>
            <a href="{{ route('medicos.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</div>
<script>
    document.getElementById('criar_bloqueio').addEventListener('change', function() {
        document.getElementById('dadosBloqueio').style.display =
            this.checked ? 'block' : 'none';
    });
</script>

@endsection