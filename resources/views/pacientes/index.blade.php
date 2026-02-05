@extends('layouts.app')

@section('titulo', 'Pacientes')

@section('conteudo')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Pacientes</h5>

        <a href="{{ route('pacientes.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Novo Paciente
        </a>
    </div>

    <div class="card-body">

        <!-- FILTRO -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-10">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Buscar por nome ou CPF"
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-2 d-grid">
                <button class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>

        <!-- TABELA -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th width="220">Ações</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($pacientes as $p)
                    <tr>
                        <td>
                            <strong>{{ $p->nome }}</strong><br>
                            <small class="text-muted">
                                CPF: {{ $p->cpf ?? 'Não informado' }}
                            </small>
                        </td>

                        <td>{{ $p->telefone }}</td>

                        <td>
                            <a href="{{ route('pacientes.historico', $p->id) }}"
                                class="btn btn-sm btn-outline-dark">
                                <i class="bi bi-clock-history"></i>
                            </a>

                            <a href="{{ route('pacientes.edit', $p) }}"
                                class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form method="POST"
                                action="{{ route('pacientes.destroy', $p) }}"
                                class="d-inline"
                                onsubmit="return confirm('Excluir paciente?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            Nenhum paciente encontrado
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- PAGINAÇÃO -->
        <div class="mt-3">
            {{ $pacientes->withQueryString()->links() }}
        </div>

    </div>
</div>

@endsection