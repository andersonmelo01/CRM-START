@extends('layouts.app')

@section('titulo', 'Agenda Médica')

@section('conteudo')
<div class="container py-4">

    <div class="d-flex justify-content-between mb-3">
        <h4>
            <i class="bi bi-calendar-week text-primary"></i>
            Agenda dos Médicos
        </h4>

        <a href="{{ route('agenda-medicos.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i>
            Nova Agenda
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Médico</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Intervalo</th>
                        <th width="80">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agendas as $a)
                    <tr>
                        <td>{{ $a->medico->nome }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->data)->format('d/m/Y') }}</td>
                        <td>{{ $a->hora_inicio }} às {{ $a->hora_fim }}</td>
                        <td>{{ $a->intervalo }} min</td>
                        <td>
                            <form method="POST" action="{{ route('agenda-medicos.destroy', $a) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Remover agenda?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Nenhuma agenda cadastrada
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection