@extends('layouts.app')

@section('titulo', 'Nova Consultas')

@section('conteudo')
<h3>Nova Consulta</h3>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<form method="POST" action="{{ route('consultas.store') }}">
    @csrf

    {{-- DADOS DA CONSULTA --}}
    <div class="row">
        <!--Paciente-->
        <div class="col-md-4">
            <label>Paciente</label>
            <div class="input-group">
                <input
                    type="text"
                    id="paciente_nome"
                    class="form-control"
                    placeholder="Selecione um paciente"
                    readonly
                    required>

                <input type="hidden" name="paciente_id" id="paciente_id">

                <button
                    type="button"
                    class="btn btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalPacientes">
                    Buscar
                </button>
            </div>
        </div>
        <!--Modal Paciente-->
        <div class="modal fade" id="modalPacientes" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Selecionar Paciente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input
                            type="text"
                            id="buscarPacienteModal"
                            class="form-control mb-3"
                            placeholder="Buscar paciente pelo nome">

                        <ul class="list-group" id="listaPacientes">
                            @foreach($pacientes as $p)
                            <li
                                class="list-group-item list-group-item-action"
                                data-id="{{ $p->id }}"
                                data-nome="{{ $p->nome }}">
                                {{ $p->nome }}
                            </li>
                            @endforeach
                        </ul>

                    </div>

                </div>
            </div>
        </div>

        <!--Médico-->
        <div class="col-md-4">
            <label>Médico</label>

            <select name="medico_id" id="medicoSelect" class="form-control" required>
                <option value="">Selecione um médico</option>
                @foreach($medicos as $m)
                <option value="{{ $m->id }}">{{ $m->nome }}</option>
                @endforeach
            </select>

            <div id="avisoBloqueioMedico" class="text-danger mt-1" style="display:none;">
                ⚠️ Médico bloqueado no momento
            </div>
        </div>


        <div class="col-md-2">
            <label>Data</label>
            <input type="date" name="data" class="form-control" required>
        </div>

        <div class="col-md-2">
            <label>Hora</label>
            <input
                type="time"
                name="hora"
                class="form-control @error('hora') is-invalid @enderror"
                value="{{ old('hora') }}"
                required>

            @error('hora')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

    </div>

    <div class="mt-3">
        <label>Observações</label>
        <textarea name="observacoes" class="form-control"></textarea>
    </div>

    {{-- PAGAMENTO --}}
    <hr class="my-4">

    <h5>Pagamento</h5>

    <div class="row">
        <div class="col-md-3">
            <label>Valor</label>
            <input
                type="number"
                step="0.01"
                name="valor"
                class="form-control"
                placeholder="R$ 0,00"
                required>
        </div>
        <div class="col-md-3">
            <label>Valor Parcela</label>
            <input
                type="number"
                step="0.01"
                name="valor_pago"
                class="form-control"
                placeholder="R$ 0,00"
                required>
        </div>
        <div class="col-md-3">
            <label>Forma de Pagamento</label>
            <select name="forma_pagamento" class="form-control" required>
                <option value="dinheiro">Dinheiro</option>
                <option value="cartao">Cartão</option>
                <option value="pix">PIX</option>
                <option value="convenio">Convênio</option>
            </select>
        </div>

        <div class="col-md-3">
            <label>Status do Pagamento</label>
            <select name="status_pagamento" class="form-control" required>
                <option value="pago">Pago</option>
                <option value="pendente">Pendente</option>
            </select>
        </div>
    </div>
    <a href="{{ route('consultas.index') }}" class="btn btn-secondary mt-4">Voltar</a>
    <button class="btn btn-primary mt-4">Agendar Consulta</button>

</form>

<script>
    const buscarInput = document.getElementById('buscarPacienteModal');
    const lista = document.getElementById('listaPacientes');

    buscarInput.addEventListener('keyup', function() {
        const filtro = this.value.toLowerCase();

        lista.querySelectorAll('li').forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(filtro) ?
                '' :
                'none';
        });
    });

    lista.querySelectorAll('li').forEach(item => {
        item.addEventListener('click', function() {
            document.getElementById('paciente_id').value = this.dataset.id;
            document.getElementById('paciente_nome').value = this.dataset.nome;

            const modal = bootstrap.Modal.getInstance(
                document.getElementById('modalPacientes')
            );
            modal.hide();
        });
    });
    document.getElementById('medicoSelect').addEventListener('change', function() {
        const medicoId = this.value;
        const aviso = document.getElementById('avisoBloqueioMedico');

        aviso.style.display = 'none';

        if (!medicoId) return;

        fetch(`/medicos/${medicoId}/status-bloqueio`)
            .then(res => res.json())
            .then(data => {
                if (data.bloqueado) {
                    aviso.style.display = 'block';
                }
            })
            .catch(() => {
                console.error('Erro ao verificar bloqueio do médico');
            });
    });
</script>


@endsection