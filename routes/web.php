<?php

use App\Http\Controllers\MedicoController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AgendamentoPublicoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProntuarioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BloqueioController;
use App\Http\Controllers\EmitenteController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PedidoExameController;
use App\Http\Controllers\PreCadastroController;
use App\Http\Controllers\RelatorioFinanceiroController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Models\Bloqueio;

Route::get('/', function () {
    return view('welcome');
});

//agendamento publico
Route::get('/agendar', [AgendamentoPublicoController::class, 'index'])->name('agendamento.publico');
Route::post('/agendar', [AgendamentoPublicoController::class, 'store'])->name('agendamento.publico.store');
Route::get('/agendamento/horarios', [AgendamentoPublicoController::class, 'horarios']);
Route::get('/agendamento/verificar-cpf', [AgendamentoPublicoController::class, 'verificarCpf']);
//pré-cadastro
Route::get('/precadastro', function () {
    return view('pre-cadastro');
});

Route::get('/pre-cadastro', [PreCadastroController::class, 'index'])->name('pre-cadastro');
Route::post('/pre-cadastro/salvar', [PreCadastroController::class, 'store'])->name('pre-cadastro.store');
//Route::post('/precadastro/salvar', [PreCadastroController::class, 'salvar']);

Route::middleware(['auth'])->group(function () {

    
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('auth')
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('pacientes', PacienteController::class);
    Route::resource('medicos', MedicoController::class);
    Route::resource('consultas', ConsultaController::class);
    Route::get('/config', [SiteController::class, 'index'])->name('config');

    Route::get('/consultas/create', [ConsultaController::class, 'create'])->name('consultas.create');
    Route::get('/consultas/{consulta}/prontuario', [ProntuarioController::class, 'create'])->name('prontuarios.create');
    Route::post('/consultas/{consulta}/prontuario', [ProntuarioController::class, 'store'])->name('prontuarios.store');
    Route::get('/consultas/{consulta}/prontuario/ver', [ProntuarioController::class, 'show'])->name('prontuarios.show');
    Route::get('/consultas/{consulta}/exames', [PedidoExameController::class, 'porConsulta'])->name('consultas.exames');
    //whatsapp
    Route::get('/consultas/{consulta}/whatsapp', [ConsultaController::class, 'whatsapp'])->name('consultas.whatsapp');
    Route::get('/consultas/{consulta}/whatsappPreCadastroConsulta', [ConsultaController::class, 'whatsappPreCadastroConsulta'])->name('consultas.whatsappPreCadastroConsulta');


    //Route::get('/prontuario', [ProntuarioController::class, 'create'])->name('prontuarios.create');
    Route::get('/prontuarios/{prontuario}', [ProntuarioController::class, 'show'])->name('prontuarios.show');
    
    Route::get('/pacientes/{paciente}/historico', [PacienteController::class, 'historico'])->name('pacientes.historico');


    Route::post('/exames/{consulta}', [PedidoExameController::class, 'store'])->name('exames.store');
    Route::put('/exames/{exame}/resultado', [PedidoExameController::class, 'updateResultado'])->name('exames.resultado');
    Route::get('/exames/{exame}/imprimir', [PedidoExameController::class, 'imprimir'])->name('exames.imprimir');

    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
    Route::get('/agenda/eventos', [AgendaController::class, 'eventos']);
    Route::put('/agenda/{id}', [AgendaController::class, 'atualizar']);
    Route::get('/agenda/consulta/{id}', [AgendaController::class, 'show']);
    Route::put('/agenda/consulta/{id}', [AgendaController::class, 'atualizarDetalhes']);
    Route::delete('/agenda/consulta/{id}', [AgendaController::class, 'excluir']);
    Route::post('/agenda', [AgendaController::class, 'criar']);


    // Rotas de bloqueios
    Route::prefix('bloqueios')->group(function () {
        Route::get('/', [\App\Http\Controllers\BloqueioController::class, 'index'])->name('bloqueios.index');
        Route::get('/create', [\App\Http\Controllers\BloqueioController::class, 'create'])->name('bloqueios.create');
        Route::post('/', [\App\Http\Controllers\BloqueioController::class, 'store'])->name('bloqueios.store');

        // Rota de exclusão (desbloquear)
        Route::delete('/{bloqueio}', [\App\Http\Controllers\BloqueioController::class, 'destroy'])->name('bloqueios.destroy');

        // Bloqueados (URL diferente!)
        Route::get('/lista', [\App\Http\Controllers\BloqueioController::class, 'bloqueados'])->name('bloqueios.bloqueados');
    });



    Route::get('/emitente', [EmitenteController::class, 'edit'])->name('emitente.edit');
    Route::post('/emitente', [EmitenteController::class, 'store'])->name('emitente.store');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{user}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::get('/usuarios/criar', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::delete('/usuarios/{user}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    Route::get('/pagamentos', [PagamentoController::class, 'index'])->name('pagamentos.index');
    Route::get('/pagamentos/create', [PagamentoController::class, 'create'])->name('pagamentos.create');
    Route::post('/pagamentos', [PagamentoController::class, 'store'])->name('pagamentos.store');
    Route::patch('/pagamentos/{pagamento}/pagar', [PagamentoController::class, 'marcarComoPago'])->name('pagamentos.pagar');
    Route::patch('/pagamentos/{pagamento}/receber', [PagamentoController::class, 'receber'])->name('pagamentos.receber');
    Route::get('/pagamentos/{pagamento}/recibo', [PagamentoController::class, 'recibo'])->name('pagamentos.recibo');
    Route::get('/relatorio-financeiro', [RelatorioFinanceiroController::class, 'index'])->name('relatorio.financeiro');

    //Route::get('/medicos/bloqueados', [MedicoController::class, 'medicosBloqueados'])->name('medicos.bloqueados');
    Route::get('/bloqueios/bloqueados', [BloqueioController::class, 'medicosBloqueados'])->name('medicos.bloqueados');

    Route::get('/medicos/{medicoId}/status-bloqueio', [MedicoController::class, 'statusBloqueio'])->name('medicos.statusBloqueio');
});
Route::get('/bloqueios/eventos', function () {
    return Bloqueio::get()->map(function ($b) {
        return [
            'title' => 'Bloqueado',
            'start' => $b->data . ' ' . ($b->hora_inicio ?? '00:00'),
            'end'   => $b->data . ' ' . ($b->hora_fim ?? '23:59'),
            'color' => '#dc3545',
            'editable' => false
        ];
    });
})->middleware('auth');
require __DIR__ . '/auth.php';
