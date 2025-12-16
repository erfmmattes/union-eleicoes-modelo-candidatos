<?php

use Illuminate\Support\Facades\Route;
// Rotas do Front
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\LoginEleicaoController;
use App\Http\Controllers\Front\VotarController;
use App\Http\Controllers\Front\ComprovanteController;
use App\Http\Controllers\Front\DocumentosController;
use App\Http\Controllers\Front\AjudaController;
use App\Http\Controllers\Front\TrocarSenhaFrontController;
use App\Http\Controllers\Front\RecuperarSenhaController;
use App\Http\Controllers\Front\PoliticaDePrivacidadeController;

// Rotas do Admin
use App\Http\Controllers\Admin\HomeAdminController;
use App\Http\Controllers\Admin\DeclaracaoEleicaoController;
use App\Http\Controllers\Admin\EleitoresAdminController;
use App\Http\Controllers\Admin\DocumentosAdminController;
use App\Http\Controllers\Admin\AjudaAdminController;
use App\Http\Controllers\Admin\SetoresAdminController;
use App\Http\Controllers\Admin\EtapaCandidatoController;
use App\Http\Controllers\Admin\EscolhaCandidatoController;
use App\Http\Controllers\Admin\DadosEleicaoController;
use App\Http\Controllers\Admin\EleitorLogadoController;
use App\Http\Controllers\Admin\ListaEleitoresController;
use App\Http\Controllers\Admin\ListaChamadaEleitoresController;
use App\Http\Controllers\Admin\RelatorioLogsEleitorController;
use App\Http\Controllers\Admin\VotanteController;
use App\Http\Controllers\Admin\NaoVotanteController;
use App\Http\Controllers\Admin\ZeresimaController;
use App\Http\Controllers\Admin\ConfiguracoesController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\PerfilController;
use App\Http\Controllers\Admin\UsuarioController;

// Rotas do Auth
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\TrocaSenhaController;

// Home do Front
Route::get('/', [HomeController::class, 'index'])->name('home.index');

// Login da Eleição do Front
Route::get('/login-eleicao', [LoginEleicaoController::class, 'index'])->name('loginEleicao.index');
Route::post('/login-eleicao/auth-dois', [LoginEleicaoController::class, 'authDois'])->name('loginEleicao.authDois');
Route::get('/eleicao-login/segunda-etapa', [LoginEleicaoController::class, 'segundaEtapa'])->name('loginEleicao.segundaEtapa');
Route::post('/eleicao-login/segunda-etapa', [LoginEleicaoController::class, 'validarSegundaEtapa'])->name('loginEleicao.validarSegundaEtapa');
Route::middleware(['eleitor.auth', 'verifica.sessao.front', 'periodo.eleicao'])->group(function () {
    Route::get('/login-eleicao/termos', [LoginEleicaoController::class, 'termos'])->middleware('verificar.aceite.termos')->name('loginEleicao.termos');
    Route::post('/login-eleicao/aceitar-os-termos', [LoginEleicaoController::class, 'aceitarTermos'])->middleware('verificar.aceite.termos')->name('loginEleicao.aceitarTermos');
    Route::get('/home-eleicao', [LoginEleicaoController::class, 'homeLogadoFront'])->name('loginEleicao.homeLogadoFront');
    Route::get('/trocar-senha-apos-login', [LoginEleicaoController::class, 'trocarSenhaAposLogin'])->name('loginEleicao.trocarSenhaAposLogin');
    Route::post('/apos-login-trocar-senha', [LoginEleicaoController::class, 'senhaTrocarAposLogin'])->name('loginEleicao.senhaTrocarAposLogin');
    Route::get('/dados-eleitor', [LoginEleicaoController::class, 'dadosEleitor'])->name('loginEleicao.dadosEleitor');
    Route::get('/login-eleicao/logout', [LoginEleicaoController::class, 'logout'])->name('loginEleicao.logout');

    // Votar do Front
    Route::get('/votar', [VotarController::class, 'index'])->name('votar.index');
    Route::post('/votar/salvar-voto', [VotarController::class, 'salvarEtapa'])->name('votar.salvarEtapa');

    // Comprovante do Front
    Route::get('/comprovante', [ComprovanteController::class, 'index'])->name('comprovante.index');
    Route::post('/receber-por-email-comprovante', [ComprovanteController::class, 'receberPorEmail'])->name('comprovante.receberPorEmail');
    Route::post('/baixar-pdf-comprovante', [ComprovanteController::class, 'baixarPdfComprovante'])->name('comprovante.baixarPdfComprovante');

    // Documentos do Front
    Route::get('/documentos', [DocumentosController::class, 'index'])->name('documentos.index');

    // Ajuda do Front
    Route::get('/ajuda', [AjudaController::class, 'index'])->name('ajuda.index');

    // Trocar Senha do Front
    Route::get('/trocar-senha', [TrocarSenhaFrontController::class, 'index'])->name('trocarSenha.index');
    Route::post('/trocar-senha', [TrocarSenhaFrontController::class, 'senhaTrocar'])->name('trocarSenha.senhaTrocar');
});

// Recuperar Senha do Front
Route::get('/recuperar-senha', [RecuperarSenhaController::class, 'index'])->name('recuperarSenha.index');
Route::post('/recuperar-senha', [RecuperarSenhaController::class, 'buscar'])->name('recuperarSenha.buscar');
Route::post('/recuperar-senha/enviar', [RecuperarSenhaController::class, 'enviarSenha'])->name('recuperarSenha.enviar');

// Política de Privacidade do Front
Route::get('/politica-de-privacidade', [PoliticaDePrivacidadeController::class, 'index'])->name('politicaDePrivacidade.index');

// Redireciona para o Admin
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Rotas do Admin
Route::prefix('admin')->name('admin.')->group(function () {

    // LOGIN
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // REGISTRO (GET → exibe form / POST → cria usuário)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // RESET DE SENHA
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // TROCAR A SENHA
    Route::get('forcar-troca-senha', [TrocaSenhaController::class, 'showForm'])->name('forcarTrocaSenha');
    Route::post('forcar-troca-senha', [TrocaSenhaController::class, 'update'])->name('forcarTrocaSenha.update');

    // Home do Admin
    Route::get('/home', [HomeAdminController::class, 'index'])->name('home');

    // Declaração do Admin
    Route::post('/declaracao-eleicao', [DeclaracaoEleicaoController::class, 'gerarPdf'])->name('declaracaoEleicao.pdf');

    // Eleitores do Admin
    Route::get('/eleitores', [EleitoresAdminController::class, 'index'])->name('adminEleitores.index');
    Route::get('/eleitores/{id}', [EleitoresAdminController::class, 'show'])->name('adminEleitores.show');
    Route::get('/criar/eleitores/create', [EleitoresAdminController::class, 'create'])->name('adminEleitores.create');
    Route::post('/eleitores', [EleitoresAdminController::class, 'store'])->name('adminEleitores.store');
    Route::get('/eleitores/{id}/edit', [EleitoresAdminController::class, 'edit'])->name('adminEleitores.edit');
    Route::put('/eleitores/{id}', [EleitoresAdminController::class, 'update'])->name('adminEleitores.update');
    Route::delete('/eleitores/{id}', [EleitoresAdminController::class, 'destroy'])->name('adminEleitores.destroy');
    Route::post('/eleitores/{id}/status', [EleitoresAdminController::class, 'status'])->name('adminEleitores.status');
    Route::get('/importar/eleitores', [EleitoresAdminController::class, 'importar'])->name('adminEleitores.importar');
    Route::post('/pre-visualizar', [EleitoresAdminController::class, 'preVisualizar'])->name('adminEleitores.preVisualizar');
    Route::post('/envia/eleitores/importar', [EleitoresAdminController::class, 'importarProcessar'])->name('adminEleitores.importarProcessar');
    
    // Início do envio de senhas para os eleitores por e-mail e sms do Admin
    Route::get('/senha/metodos-de-enviar-senha', [EleitoresAdminController::class, 'metodosDeEnviarSenha'])->name('adminEleitores.metodosDeEnviarSenha');
    Route::get('/senha/eleitores', [EleitoresAdminController::class, 'enviarSenha'])->name('adminEleitores.enviarSenha');
    Route::post('/envia/eleitores/abortar', [EleitoresAdminController::class, 'abortarEnvio'])->name('adminEleitores.abortarEnvio');
    
    // Início do envio de senhas para os eleitores por e-mail do Admin
    Route::get('/senha/eleitores/enviar-senha-para-todos-por-email', [EleitoresAdminController::class, 'enviarSenhaParaTodosPorEmail'])->name('adminEleitores.enviarSenhaParaTodosPorEmail');
    Route::get('/enviar-senha-para-todos-por-email/senha/eleitores', [EleitoresAdminController::class, 'formEnviarSenhaParaTodosPorEmail'])->name('adminEleitores.formEnviarSenhaParaTodosPorEmail');
    Route::post('/envia/eleitores/abortarEmail', [EleitoresAdminController::class, 'abortarEnvioEmail'])->name('adminEleitores.abortarEnvioEmail');
    
    // Início do envio de senhas para os eleitores por sms do Admin
    Route::get('/senha/eleitores/enviar-senha-para-todos-por-sms', [EleitoresAdminController::class, 'enviarSenhaParaTodosPorSms'])->name('adminEleitores.enviarSenhaParaTodosPorSms');
    Route::get('/enviar-senha-para-todos-por-sms/senha/eleitores', [EleitoresAdminController::class, 'formEnviarSenhaParaTodosPorSms'])->name('adminEleitores.formEnviarSenhaParaTodosPorSms');
    Route::post('/envia/eleitores/abortarSms', [EleitoresAdminController::class, 'abortarEnvioSms'])->name('adminEleitores.abortarEnvioSms');
    
    // Início do envio de senhas para os não votantes por e-mail e sms do Admin
    Route::get('/senha/eleitores/nao-votantes', [EleitoresAdminController::class, 'enviarSenhaNaoVotantes'])->name('adminEleitores.enviarSenhaNaoVotantes');
    Route::get('/enviar-senha-para-todos-nao-votantes/senha/eleitores', [EleitoresAdminController::class, 'formEnviarSenhaParaTodosNaoVotantes'])->name('adminEleitores.formEnviarSenhaParaTodosNaoVotantes');
    Route::post('/envia/eleitores/abortar-nao-votantes', [EleitoresAdminController::class, 'abortarEnvioNaoVotantes'])->name('adminEleitores.abortarEnvioNaoVotantes');
    
    // Início do envio de senhas para os não votantes por e-mail do Admin
    Route::get('/senha/eleitores/nao-votantes-por-email', [EleitoresAdminController::class, 'enviarSenhaNaoVotantesPorEmail'])->name('adminEleitores.enviarSenhaNaoVotantesPorEmail');
    Route::get('/enviar-senha-para-todos-nao-votantes-por-email/senha/eleitores', [EleitoresAdminController::class, 'formEnviarSenhaParaTodosNaoVotantesPorEmail'])->name('adminEleitores.formEnviarSenhaParaTodosNaoVotantesPorEmail');
    Route::post('/envia/eleitores/abortar-nao-votantes-por-email', [EleitoresAdminController::class, 'abortarEnvioNaoVotantesPorEmail'])->name('adminEleitores.abortarEnvioNaoVotantesPorEmail');
    
    // Início do envio de senhas para os não votantes por sms do Admin
    Route::get('/senha/eleitores/nao-votantes-por-sms', [EleitoresAdminController::class, 'enviarSenhaNaoVotantesPorSms'])->name('adminEleitores.enviarSenhaNaoVotantesPorSms');
    Route::get('/enviar-senha-para-todos-nao-votantes-por-sms/senha/eleitores', [EleitoresAdminController::class, 'formEnviarSenhaParaTodosNaoVotantesPorSms'])->name('adminEleitores.formEnviarSenhaParaTodosNaoVotantesPorSms');
    Route::post('/envia/eleitores/abortar-nao-votantes-por-sms', [EleitoresAdminController::class, 'abortarEnvioNaoVotantesPorSms'])->name('adminEleitores.abortarEnvioNaoVotantesPorSms');
    
    // Início do envio de senhas para os eleitores individual por e-mail e sms do Admin
    Route::get('/massa/eleitores/enviar-senhas', [EleitoresAdminController::class, 'enviarSenhas'])->name('adminEleitores.enviarSenhas');
    Route::get('/individualmente/eleitores/{id}/enviar-senha', [EleitoresAdminController::class, 'individualEnviarSenha'])->name('adminEleitores.individualEnviarSenha');

    // Documentos do Admin
    Route::get('/documentos', [DocumentosAdminController::class, 'index'])->name('adminDocumentos.index');
    Route::post('/status/documentos/{id}', [DocumentosAdminController::class, 'status'])->name('adminDocumentos.status');
    Route::get('/documentos/{id}', [DocumentosAdminController::class, 'show'])->name('adminDocumentos.show');
    Route::get('/criar/documentos/create', [DocumentosAdminController::class, 'create'])->name('adminDocumentos.create');
    Route::post('/documentos', [DocumentosAdminController::class, 'store'])->name('adminDocumentos.store');
    Route::get('/documentos/{id}/edit', [DocumentosAdminController::class, 'edit'])->name('adminDocumentos.edit');
    Route::put('/documentos/{id}', [DocumentosAdminController::class, 'update'])->name('adminDocumentos.update');
    Route::delete('/documentos/{id}', [DocumentosAdminController::class, 'destroy'])->name('adminDocumentos.destroy');

    // Ajuda do Admin
    Route::get('/ajuda', [AjudaAdminController::class, 'index'])->name('adminAjuda.index');
    Route::get('/ajuda/create', [AjudaAdminController::class, 'create'])->name('adminAjuda.create');
    Route::post('/ajuda', [AjudaAdminController::class, 'store'])->name('adminAjuda.store');
    Route::get('/ajuda/{id}/edit', [AjudaAdminController::class, 'edit'])->name('adminAjuda.edit');
    Route::put('/ajuda/{id}', [AjudaAdminController::class, 'update'])->name('adminAjuda.update');
    Route::get('/ajuda/{id}', [AjudaAdminController::class, 'show'])->name('adminAjuda.show');
    Route::delete('/ajuda/{id}', [AjudaAdminController::class, 'destroy'])->name('adminAjuda.destroy');
    Route::post('/ajuda/{id}/status', [AjudaAdminController::class, 'status'])->name('adminAjuda.status');

    // Setores do Admin
    Route::get('/setores', [SetoresAdminController::class, 'index'])->name('adminSetor.index');
    Route::get('/setores/criar', [SetoresAdminController::class, 'create'])->name('adminSetor.create');
    Route::post('/setores', [SetoresAdminController::class, 'store'])->name('adminSetor.store');
    Route::get('/setores/{id}/editar', [SetoresAdminController::class, 'edit'])->name('adminSetor.edit');
    Route::put('/setores/{id}', [SetoresAdminController::class, 'update'])->name('adminSetor.update');
    Route::get('/setores/{id}', [SetoresAdminController::class, 'show'])->name('adminSetor.show');
    Route::delete('/setores/{id}', [SetoresAdminController::class, 'destroy'])->name('adminSetor.destroy');
    Route::post('/setores/{id}/status', [SetoresAdminController::class, 'toggleStatus'])->name('adminSetor.status');

    // Etapas do Admin
    Route::get('/etapas', [EtapaCandidatoController::class, 'index'])->name('adminEtapa.index');
    Route::get('/etapas/create', [EtapaCandidatoController::class, 'create'])->name('adminEtapa.create');
    Route::post('/etapas', [EtapaCandidatoController::class, 'store'])->name('adminEtapa.store');
    Route::get('/etapas/{id}', [EtapaCandidatoController::class, 'show'])->name('adminEtapa.show');
    Route::get('/etapas/{id}/edit', [EtapaCandidatoController::class, 'edit'])->name('adminEtapa.edit');
    Route::put('/etapas/{id}', [EtapaCandidatoController::class, 'update'])->name('adminEtapa.update');
    Route::delete('/etapas/{id}', [EtapaCandidatoController::class, 'destroy'])->name('adminEtapa.destroy');
    Route::post('/1/etapas/abrir/{id}', [EtapaCandidatoController::class, 'abrir'])->name('adminEtapa.abrir');
    Route::post('/2/etapas/pular/{id}', [EtapaCandidatoController::class, 'pular'])->name('adminEtapa.pular');
    Route::post('/3/etapas/finalizar/{id}', [EtapaCandidatoController::class, 'finalizar'])->name('adminEtapa.finalizar');

    // Escolhas do Admin
    Route::get('/escolhas', [EscolhaCandidatoController::class, 'index'])->name('adminEscolhas.index');
    Route::get('/escolhas/create', [EscolhaCandidatoController::class, 'create'])->name('adminEscolhas.create');
    Route::post('/escolhas', [EscolhaCandidatoController::class, 'store'])->name('adminEscolhas.store');
    Route::get('/escolhas/{id}', [EscolhaCandidatoController::class, 'show'])->name('adminEscolhas.show');
    Route::get('/escolhas/{id}/edit', [EscolhaCandidatoController::class, 'edit'])->name('adminEscolhas.edit');
    Route::put('/escolhas/{id}', [EscolhaCandidatoController::class, 'update'])->name('adminEscolhas.update');
    Route::delete('/escolhas/{id}', [EscolhaCandidatoController::class, 'destroy'])->name('adminEscolhas.destroy');
    Route::post('escolhas/{id}/status', [EscolhaCandidatoController::class, 'toggleStatus'])->name('adminEscolhas.status');

    // Dados da Eleição do Admin
    Route::get('/dados-eleicao', [DadosEleicaoController::class, 'index'])->name('adminDadosEleicao.index');
    Route::post('/dados-eleicao/pdf', [DadosEleicaoController::class, 'gerarPdf'])->name('adminDadosEleicao.pdf');

    // Eleitores Logados do Admin
    Route::get('/eleitores-logados', [EleitorLogadoController::class, 'index'])->name('adminEleitorLogado.index');
    Route::get('/eleitores-logados/{id}', [EleitorLogadoController::class, 'show'])->name('adminEleitorLogado.show');
    Route::post('/eleitores-logados/gerar-pdf', [EleitorLogadoController::class, 'gerarPdf'])->name('adminEleitorLogado.gerarPdf');
    Route::post('/eleitores-logados/exportar-excel', [EleitorLogadoController::class, 'exportarExcel'])->name('adminEleitorLogado.exportarExcel');

    // Lista de Eleitores do Admin
    Route::get('/lista-de-eleitores', [ListaEleitoresController::class, 'index'])->name('adminListaEleitores.index');
    Route::get('/lista-de-eleitores/{id}', [ListaEleitoresController::class, 'show'])->name('adminListaEleitores.show');
    Route::post('/lista-de-eleitores/gerar-pdf', [ListaEleitoresController::class, 'gerarPdf'])->name('adminListaEleitores.gerarPdf');
    Route::post('/lista-de-eleitores/exportar-excel', [ListaEleitoresController::class, 'exportarExcel'])->name('adminListaEleitores.exportarExcel');

    // Lista de Chamada do Admin
    Route::get('/lista-de-chamada', [ListaChamadaEleitoresController::class, 'index'])->name('adminListaChamada.index');
    Route::get('/lista-de-chamada/{id}', [ListaChamadaEleitoresController::class, 'show'])->name('adminListaChamada.show');
    Route::post('/lista-de-chamada/gerar-pdf', [ListaChamadaEleitoresController::class, 'gerarPdf'])->name('adminListaChamada.gerarPdf');
    Route::post('/lista-de-chamada/exportar-excel', [ListaChamadaEleitoresController::class, 'exportarExcel'])->name('adminListaChamada.exportarExcel');

    // Relatório de Logs do Eleitor do Admin
    Route::get('/relatorio-de-logs-do-eleitor', [RelatorioLogsEleitorController::class, 'index'])->name('adminRelatorioDeLogsDoEleitor.index');
    Route::post('/relatorio-de-logs-do-eleitor', [RelatorioLogsEleitorController::class, 'store'])->name('adminRelatorioDeLogsDoEleitor.store');
    Route::get('/relatorio-de-logs-do-eleitor/{id}', [RelatorioLogsEleitorController::class, 'show'])->name('adminRelatorioDeLogsDoEleitor.show');
    Route::delete('/relatorio-de-logs-do-eleitor/{id}', [RelatorioLogsEleitorController::class, 'destroy'])->name('adminRelatorioDeLogsDoEleitor.destroy');
    Route::post('/baixar/relatorio-logs-eleitor/pdf', [RelatorioLogsEleitorController::class, 'gerarPdf'])->name('adminRelatorioDeLogsDoEleitor.pdf');
    Route::post('/exel/logs-eleitor/gerar', [RelatorioLogsEleitorController::class, 'gerarExcel'])->name('adminRelatorioDeLogsDoEleitor.excel');

    // Votantes do Admin
    Route::get('/votantes', [VotanteController::class, 'index'])->name('adminVotantes.index');
    Route::get('/votantes/{id}', [VotanteController::class, 'show'])->name('adminVotantes.show');
    Route::post('/votantes/gerar-pdf', [VotanteController::class, 'gerarPdf'])->name('adminVotantes.gerarPdf');
    Route::post('/votantes/exportar-excel', [VotanteController::class, 'exportarExcel'])->name('adminVotantes.exportarExcel');

    // Não Votantes do Admin
    Route::get('/nao-votantes', [NaoVotanteController::class, 'index'])->name('adminNaoVotantes.index');
    Route::get('/nao-votantes/{id}', [NaoVotanteController::class, 'show'])->name('adminNaoVotantes.show');
    Route::post('/nao-votantes/gerar-pdf', [NaoVotanteController::class, 'gerarPdf'])->name('adminNaoVotantes.gerarPdf');
    Route::post('/nao-votantes/exportar-excel', [NaoVotanteController::class, 'exportarExcel'])->name('adminNaoVotantes.exportarExcel');

    // Zerézima do Admin
    Route::get('/zeresima', [ZeresimaController::class, 'index'])->name('adminZeresima.index');
    Route::post('/zeresima/gerar-pdf', [ZeresimaController::class, 'gerarPdf'])->name('adminZeresima.gerarPdf');

    // Configurações do Admin
    Route::get('/configuracoes', [ConfiguracoesController::class, 'index'])->name('adminConfiguracoes.index');
    Route::post('/configuracoes/{id}', [ConfiguracoesController::class, 'update'])->name('adminConfiguracoes.update');
    Route::post('/enviar/configuracoes/verificar-senha', [ConfiguracoesController::class, 'verificarSenha'])->name('adminConfiguracoes.verificarSenha');
    Route::delete('/configuracoes/reiniciar-eleicao', [ConfiguracoesController::class, 'reiniciarEleicao'])->name('adminConfiguracoes.reiniciarEleicao');

    // Logs do Admin
    Route::get('/logs', [LogsController::class, 'index'])->name('adminLogs.index');
    Route::delete('/logs/{id}', [LogsController::class, 'destroy'])->name('adminLogs.destroy');
    Route::get('/logs/{id}', [LogsController::class, 'show'])->name('adminLogs.show');

    // Perfil do Admin
    Route::get('/perfil', [PerfilController::class, 'index'])->name('adminPerfil.index');
    Route::post('/perfil/atualizar', [PerfilController::class, 'atualizar'])->name('adminPerfil.atualizar');

    // Usuários do Admin
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('adminUsuario.index');
    Route::get('/criar/usuarios', [UsuarioController::class, 'create'])->name('adminUsuario.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('adminUsuario.store');
    Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])->name('adminUsuario.show');
    Route::get('/{id}/usuario/editar', [UsuarioController::class, 'edit'])->name('adminUsuario.edit');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('adminUsuario.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('adminUsuario.destroy');
    Route::post('/status/usuarios/{id}', [UsuarioController::class, 'toggleStatus'])->name('adminUsuario.status');
});




// Rota que retorna a hora do servidor
Route::get('/hora-servidor', function() {
    return response()->json([
        'hora' => \Carbon\Carbon::now('America/Sao_Paulo')->format('Y-m-d H:i:s')
    ]);
})->name('hora.servidor');