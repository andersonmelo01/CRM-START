@extends('layouts.app')

@section('titulo', 'Médicos')

@section('conteudo')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-person-badge me-2"></i>Médicos
        </h4>

        <a href="{{ route('medicos.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i>Novo Médico
        </a>
    </div>

    {{-- ALERTA --}}
    @if(session('success'))
    <div class="alert alert-success shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- CARD --}}
    <div class="card shadow-sm">

        {{-- BUSCA --}}
        <div class="card-body border-bottom">
            <form method="GET">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                        name="busca"
                        class="form-control"
                        placeholder="Buscar por nome, CRM ou especialidade"
                        value="{{ request('busca') }}">
                </div>
            </form>
        </div>

        {{-- TABELA --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>CRM</th>
                        <th>Especialidade</th>
                        <th>Status</th>
                        <th class="text-center" width="160">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($medicos as $m)
                    <tr>
                        <td class="fw-semibold">{{ $m->nome }}</td>
                        <td>{{ $m->crm }}</td>
                        <td>{{ $m->especialidade }}</td>
                        <td>
                            @if($m->estaBloqueadoEm(now()->format('Y-m-d'), now()->format('H:i')))
                            <span class="badge bg-danger">
                                <i class="bi bi-lock-fill me-1"></i>Bloqueado
                            </span>
                            @else
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Disponível
                            </span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('medicos.edit', $m) }}"
                                class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('medicos.destroy', $m) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Deseja excluir este médico?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-info-circle me-1"></i>
                            Nenhum médico encontrado
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINAÇÃO --}}
        <div class="card-footer bg-white">
            {{ $medicos->withQueryString()->links() }}
        </div>

    </div>
</div>

@endsection