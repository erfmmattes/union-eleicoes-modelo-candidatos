<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Admin\EleitoresAdminService;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class EleitoresAdminController extends Controller
{
    protected EleitoresAdminService $eleitoresAdminService;
    protected ListaUsuarioTelaPermissaoService $permissaoService;

    public function __construct(
        EleitoresAdminService $eleitoresAdminService,
        ListaUsuarioTelaPermissaoService $permissaoService
    ) {
        $this->middleware('auth');
        $this->eleitoresAdminService = $eleitoresAdminService;
        $this->permissaoService = $permissaoService;
    }

    public function index(Request $request)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $eleitores = $this->eleitoresAdminService->listarTodosComFiltro($request->only(['q', 'status']));
        return view('adminEleitores.index', compact('todasPermissoes', 'eleitores'));
    }

    public function show(int $id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['ver'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $eleitor = $this->eleitoresAdminService->buscarPorId($id);

        if (!$eleitor) {
            return redirect()->route('admin.adminEleitores.index')
                ->with('error', 'Eleitor não encontrado.');
        }

        return view('adminEleitores.show', compact('eleitor'));
    }

    public function create()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['criar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminEleitores.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'status' => $request->has('status'),
        ]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'campo_opcional' => 'nullable|string|max:255',
            'celular' => 'required|string|max:20',
            'setor' => 'nullable|string|max:255',
            'peso_do_voto' => 'nullable|string|max:50',
            'email' => 'required|email|unique:eleitores,email',
            'cpf_cnpj' => 'required|string|max:18|unique:eleitores,cpf_cnpj',
            'data_nascimento' => 'nullable|date',
            'nome_do_representante' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $this->eleitoresAdminService->criar($validated);

        return redirect()->route('admin.adminEleitores.index')->with('success', 'Eleitor criado com sucesso!');
    }

    public function edit(int $id)
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['editar'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        $eleitor = $this->eleitoresAdminService->buscarPorId($id);
        return view('adminEleitores.edit', compact('eleitor'));
    }

    public function update(Request $request, int $id)
    {
        $request->merge([
            'status' => $request->has('status'),
        ]);

        $validated = $request->validate([
            'nome' => 'nullable|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'campo_opcional' => 'nullable|string|max:255',
            'celular' => 'nullable|string|max:20',
            'setor' => 'nullable|string|max:255',
            'peso_do_voto' => 'nullable|string|max:50',
            'email' => 'required|email|unique:eleitores,email,' . $id,
            'cpf_cnpj' => 'required|string|max:18|unique:eleitores,cpf_cnpj,' . $id,
            'data_nascimento' => 'nullable|date',
            'nome_do_representante' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        $this->eleitoresAdminService->atualizar($id, $validated);

        return redirect()->route('admin.adminEleitores.index')->with('success', 'Eleitor atualizado com sucesso!');
    }

    public function destroy(int $id)
    {
        $this->eleitoresAdminService->excluir($id);
        return redirect()->route('admin.adminEleitores.index')->with('success', 'Eleitor excluído com sucesso!');
    }

    public function status(int $id)
    {
        $status = $this->eleitoresAdminService->alternarStatus($id);

        return response()->json([
            'success' => true,
            'status' => $status,
        ]);
    }

    public function importar()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['importar_eleitores'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminEleitores.importar');
    }

    public function preVisualizar(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:csv,xls,xlsx',
        ]);

        $arquivo = $request->file('arquivo');
        $nomeArquivo = 'eleitores_' . uniqid() . '.' . $arquivo->getClientOriginalExtension();
        $arquivo->storeAs('eleitores', $nomeArquivo, 'public');

        $camposBanco = $this->eleitoresAdminService->camposEle();
        $dados = $this->eleitoresAdminService->lerArquivo($nomeArquivo, 'public/eleitores');

        return view('adminEleitores.mapeamento', [
            'arquivoTemp' => $nomeArquivo,
            'cabecalhos' => $dados['cabecalhos'],
            'linhas' => $dados['linhas'],
            'camposBanco' => $camposBanco,
        ]);
    }

    public function importarProcessar(Request $request)
    {
        $mapeamento = $request->input('mapeamento', []);
        $arquivoTemp = $request->input('arquivoTemp');

        try {
            $total = $this->eleitoresAdminService->processarImportacao($arquivoTemp, $mapeamento);

            return redirect()
                ->route('admin.adminEleitores.index')
                ->with('success', "Importação concluída com sucesso! {$total} eleitores foram importados.");
        } catch (\Exception $e) {
            \Log::error('Erro ao importar eleitores: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Falha na importação. Verifique o arquivo e tente novamente.');
        }
    }

    public function metodosDeEnviarSenha()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['enviar_senha'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminEleitores.metodosDeEnviarSenha');
    }

    public function enviarSenha()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['enviar_senha'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminEleitores.enviarSenha');
    }

    public function enviarSenhas()
    {
        Cache::forget('abort_envio_senhas');

        $eleitores = $this->eleitoresAdminService->enviarSenhasParaTodos();

        return redirect()
            ->back()
            ->with('success', "Senhas enviadas: {$eleitores['enviadas']} e puladas: {$eleitores['puladas']}.");
    }

    public function abortarEnvio()
    {
        Cache::put('abort_envio_senhas', true, now()->addMinutes(10));
        return response()->json(['status' => 'aborto_registrado']);
    }

    public function enviarSenhaParaTodosPorEmail()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['enviar_senha'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminEleitores.enviarSenhaParaTodosPorEmail');
    }

    public function formEnviarSenhaParaTodosPorEmail()
    {
        Cache::forget('abort_envio_senhas');

        $eleitores = $this->eleitoresAdminService->enviarSenhasParaTodosPorEmail();

        return redirect()
            ->back()
            ->with('success', "Senhas enviadas: {$eleitores['enviadas']} e puladas: {$eleitores['puladas']}.");
    }

    public function abortarEnvioEmail()
    {
        Cache::put('abort_envio_senhas', true, now()->addMinutes(10));
        return response()->json(['status' => 'aborto_registrado']);
    }

    public function enviarSenhaParaTodosPorSms()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['enviar_senha'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminEleitores.enviarSenhaParaTodosPorSms');
    }

    public function formEnviarSenhaParaTodosPorSms()
    {
        Cache::forget('abort_envio_senhas');

        $eleitores = $this->eleitoresAdminService->enviarSenhasParaTodosPorSms();

        return redirect()
            ->back()
            ->with('success', "Senhas enviadas: {$eleitores['enviadas']} e puladas: {$eleitores['puladas']}.");
    }

    public function abortarEnvioSms()
    {
        Cache::put('abort_envio_senhas', true, now()->addMinutes(10));
        return response()->json(['status' => 'aborto_registrado']);
    }

    public function enviarSenhaNaoVotantes()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['enviar_senha'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminEleitores.enviarSenhaNaoVotantes');
    }

    public function formEnviarSenhaParaTodosNaoVotantes()
    {
        Cache::forget('abort_envio_senhas');

        $eleitores = $this->eleitoresAdminService->enviarSenhasParaTodosNaoVotantes();

        return redirect()
            ->back()
            ->with('success', "Senhas enviadas: {$eleitores['enviadas']} e puladas: {$eleitores['puladas']}.");
    }

    public function abortarEnvioNaoVotantes()
    {
        Cache::put('abort_envio_senhas', true, now()->addMinutes(10));
        return response()->json(['status' => 'aborto_registrado']);
    }

    public function enviarSenhaNaoVotantesPorEmail()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['enviar_senha'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminEleitores.enviarSenhaNaoVotantesPorEmail');
    }

    public function formEnviarSenhaParaTodosNaoVotantesPorEmail()
    {
        Cache::forget('abort_envio_senhas');

        $eleitores = $this->eleitoresAdminService->enviarSenhasParaTodosNaoVotantesPorEmail();

        return redirect()
            ->back()
            ->with('success', "Senhas enviadas: {$eleitores['enviadas']} e puladas: {$eleitores['puladas']}.");
    }

    public function abortarEnvioNaoVotantesPorEmail()
    {
        Cache::put('abort_envio_senhas', true, now()->addMinutes(10));
        return response()->json(['status' => 'aborto_registrado']);
    }

    public function enviarSenhaNaoVotantesPorSms()
    {
        $todasPermissoes = $this->permissaoService->getTodasPermissoes();
        if (empty($todasPermissoes['eleitores']['enviar_senha'] ?? false)) {
            abort(403, 'Você não tem permissão para visualizar esta página.');
        }
        return view('adminEleitores.enviarSenhaNaoVotantesPorSms');
    }

    public function formEnviarSenhaParaTodosNaoVotantesPorSms()
    {
        Cache::forget('abort_envio_senhas');

        $eleitores = $this->eleitoresAdminService->enviarSenhasParaTodosNaoVotantesPorSms();

        return redirect()
            ->back()
            ->with('success', "Senhas enviadas: {$eleitores['enviadas']} e puladas: {$eleitores['puladas']}.");
    }

    public function abortarEnvioNaoVotantesPorSms()
    {
        Cache::put('abort_envio_senhas', true, now()->addMinutes(10));
        return response()->json(['status' => 'aborto_registrado']);
    }

    public function individualEnviarSenha($id)
    {
        $resultado = $this->eleitoresAdminService->individualEnviarSenha($id);

        if ($resultado['status'] === 'success') {
            return redirect()->back()->with('success', $resultado['message']);
        }

        return redirect()->back()->with('error', $resultado['message']);
    }
}