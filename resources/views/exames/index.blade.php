@extends('layouts.app')

@section('titulo','Exames da Consulta')

@section('conteudo')
<div class="container">

    {{-- ALERTAS --}}
    @if(session('success'))
    <div class="alert alert-success shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">🧪 Exames da Consulta</h3>

        <a href="{{ route('consultas.index') }}" class="btn btn-outline-secondary">
            ← Voltar Agenda
        </a>
    </div>


    {{-- CARD INFO CONSULTA --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white">
            Dados da Consulta
        </div>

        <div class="card-body">
            <div class="row text-center">

                <div class="col-md-4 mb-2">
                    <div class="fw-semibold text-muted">Paciente</div>
                    <div class="fs-5">{{ $consulta->paciente->nome }}</div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="fw-semibold text-muted">Médico</div>
                    <div class="fs-5">{{ $consulta->medico->nome }}</div>
                </div>

                <div class="col-md-4 mb-2">
                    <div class="fw-semibold text-muted">Data / Hora</div>
                    <div class="fs-5">
                        {{ \Carbon\Carbon::parse($consulta->data)->format('d/m/Y') }}
                        — {{ $consulta->hora }}
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- NOVO EXAME --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            ➕ Solicitar Novo Exame
        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('exames.store', $consulta->id) }}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label>Tipo</label>
                        <select name="tipo_exame" class="form-select">
                            <option value="">Selecione...</option>

                            <optgroup label="Laboratoriais">
                                <option>Hemograma Completo</option>
                                <option>Glicemia</option>
                                <option>Colesterol Total</option>
                                <option>HDL</option>
                                <option>LDL</option>
                                <option>Triglicerídeos</option>
                                <option>TSH</option>
                                <option>T4 Livre</option>
                                <option>Urina Tipo I</option>
                                <option>Creatinina</option>
                                <option>Ureia</option>
                            </optgroup>

                            <optgroup label="Imagem">
                                <option>Raio-X</option>
                                <option>Ultrassom</option>
                                <option>Ressonância</option>
                                <option>Tomografia</option>
                            </optgroup>

                            <optgroup label="Cardiológicos">
                                <option>Eletrocardiograma</option>
                                <option>Ecocardiograma</option>
                            </optgroup>

                            <option value="outro">Outro exame...</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <label>Descrição</label>
                        <textarea name="descricao" rows="3" class="form-control"></textarea>
                    </div>
                </div>

                <button class="btn btn-warning mt-3">
                    Solicitar Exame
                </button>
            </form>

        </div>
    </div>


    {{-- LISTA DE EXAMES --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-light fw-semibold">
            📋 Exames Solicitados
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Exame</th>
                        <th>Data</th>
                        <th width="160">Status</th>
                        <th>Resultado</th>
                        <th width="120">Imprimir</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($consulta->exames as $ex)
                    <tr>
                        <td class="fw-semibold">
                            {{ $ex->tipo_exame }}
                        </td>
                        <td class="fw-semibold">
                            {{ $ex->data_solicitacao }}
                        </td>
                        <td>
                            @if($ex->status == 'solicitado')
                            <span class="badge bg-warning text-dark">
                                Solicitado
                            </span>
                            @elseif($ex->status == 'pronto')
                            <span class="badge bg-success">
                                Pronto
                            </span>
                            @else
                            <span class="badge bg-secondary">
                                {{ $ex->status }}
                            </span>
                            @endif
                        </td>

                        <td>
                            {{ $ex->resultado ?? '—' }}
                        </td>
                        <td>
                            <a href="{{ route('exames.imprimir', $ex) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            Nenhum exame solicitado ainda
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>

</div>


{{-- SCRIPT --}}
<script>
    function toggleOutroExame() {
        const sel = document.getElementById('exame_padrao');
        const box = document.getElementById('outro_exame_box');

        box.classList.toggle('d-none', sel.value !== 'outro');
    }
</script>

@endsection