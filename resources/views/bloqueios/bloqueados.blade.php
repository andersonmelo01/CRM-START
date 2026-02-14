@extends('layouts.app')

@section('titulo', 'Médicos Bloqueados')

@section('conteudo')
<div class="container py-4">

    {{-- Cabeçalho --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-person-badge me-2"></i>Médicos Bloqueados
        </h4>
        <a href="/config" class="btn btn-outline-secondary">
            <i class="bi bi-lock me-1"></i> Voltar
        </a>
        <a href="/bloqueios" class="btn btn-outline-secondary">
            <i class="bi bi-lock me-1"></i> Adicionar Bloqueios
        </a>
    </div>

    @if($medicos->isEmpty() || $medicos->every(fn($m) => $m->bloqueios->isEmpty()))
    <div class="alert alert-info text-center">
        Nenhum médico bloqueado no momento.
    </div>
    @else
    {{-- Tabela Responsiva --}}
    <div class="card shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Médico</th>
                        <th>Horário Bloqueado</th>
                        <th>Motivo</th>
                        <th>Ações</th> {{-- Nova coluna --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicos as $medico)
                    @foreach($medico->bloqueios as $b)
                    <tr class="table-warning">
                        <td>
                            <i class="bi bi-person-badge me-1 text-primary"></i>
                            {{ $medico->nome }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($b->data)->format('d/m/Y') }}
                            <br>
                            <span class="text-muted">{{ $b->hora_inicio }} - {{ $b->hora_fim }}</span>
                        </td>
                        <td>{{ $b->motivo }}</td>
                        <td>
                            {{-- Botão de desbloqueio --}}
                            <form action="{{ route('bloqueios.destroy', $b) }}" method="POST" onsubmit="return confirm('Deseja realmente desbloquear este horário?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-unlock me-1"></i> Desbloquear
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection