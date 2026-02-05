@extends('layouts.app')

@section('titulo', 'Configurações')

@section('conteudo')

<div class="container-fluid">

    <!-- TÍTULO -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-gear me-2 text-primary"></i>
            Configurações do Sistema
        </h4>
    </div>

    <!-- CARD -->
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row g-3">

                <!-- BLOQUEIOS -->
                <div class="col-md-3">
                    <a href="{{ route('medicos.bloqueados') }}" class="card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-slash-circle text-danger fs-2 mb-2"></i>
                            <h6 class="mb-1">Bloqueios</h6>
                            <small class="text-muted">Médicos bloqueados</small>
                        </div>
                    </a>
                </div>

                <!-- USUÁRIOS -->
                <div class="col-md-3">
                    <a href="{{ route('usuarios.index') }}" class="card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-people text-primary fs-2 mb-2"></i>
                            <h6 class="mb-1">Usuários</h6>
                            <small class="text-muted">Controle de acesso</small>
                        </div>
                    </a>
                </div>

                <!-- EMITENTE -->
                <div class="col-md-3">
                    <a href="{{ route('emitente.edit') }}" class="card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-file-earmark-text text-success fs-2 mb-2"></i>
                            <h6 class="mb-1">Emitente</h6>
                            <small class="text-muted">Dados fiscais</small>
                        </div>
                    </a>
                </div>

                <!-- RELATÓRIOS -->
                <div class="col-md-3">
                    <a href="{{ route('relatorio.financeiro') }}" class="card text-decoration-none h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-bar-chart-line text-warning fs-2 mb-2"></i>
                            <h6 class="mb-1">Relatórios</h6>
                            <small class="text-muted">Financeiro</small>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>

</div>

@endsection