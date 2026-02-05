@extends('layouts.app')

@section('titulo', 'Dashboard')

@section('conteudo')

@if (auth()->user()->name)

@endif
<div class="container">

    <h3 class="mb-4">📊 Dashboard</h3>

    <div class="row g-3">

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Pacientes</h6>
                    <h2>{{ $totalPacientes }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Consultas Hoje</h6>
                    <h2>{{ $consultasHoje }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Consultas do Mês</h6>
                    <h2>{{ $consultasMes }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h6 class="text-muted">Atendidas</h6>
                    <h2 class="text-success">{{ $atendidas }}</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- Agenda do Dia -->
    <div class="row mt-4">

        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>🕒 Agenda de Hoje</strong>
                </div>

                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendaHoje as $c)
                        <tr>
                            <td>{{ $c->hora }}</td>
                            <td>{{ $c->paciente->nome }}</td>
                            <td>
                                <span class="badge 
                                        @if($c->status=='agendada') bg-warning
                                        @elseif($c->status=='atendida') bg-success
                                        @else bg-danger @endif">
                                    {{ ucfirst($c->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Nenhuma consulta hoje
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

        <!-- Gráfico -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>📈 Consultas por Status</strong>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <div style="width: 220px; height: 220px;">
                            <canvas id="chartConsultas"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Financeiro -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card p-3 bg-success text-white">
                    Total Recebido <br>
                    R$ {{ number_format($totalRecebido,2,',','.') }}
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 bg-warning">
                    Pendentes <br>
                    R$ {{ number_format($totalPendente,2,',','.') }}
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 bg-info text-white">
                    Hoje <br>
                    R$ {{ number_format($totalHoje,2,',','.') }}
                </div>
            </div>
        </div>

    </div>

</div>
<script>
    const atendidas = @json($atendidas ?? 0);
    const canceladas = @json($canceladas ?? 0);
    const totalMes = @json($consultasMes ?? 0);

    const agendadas = totalMes - atendidas - canceladas;

    new Chart(document.getElementById('chartConsultas'), {
        type: 'doughnut',
        data: {
            labels: ['Atendidas', 'Canceladas', 'Agendadas'],
            datasets: [{
                data: [atendidas, canceladas, agendadas],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection