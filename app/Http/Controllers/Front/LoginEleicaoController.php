<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\Front\LoginEleicaoService;

class LoginEleicaoController extends Controller
{
    protected $loginEleicaoService;

    public function __construct(LoginEleicaoService $loginEleicaoService)
    {
        $this->middleware(['sessao.unica.front', 'periodo.eleicao']);
        $this->loginEleicaoService = $loginEleicaoService;
    }

    public function index()
    {
        $dados = $this->loginEleicaoService->getDadosLoginEleicao();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }

        return view('loginEleicao.index', compact('dados'));
    }

    public function authDois(Request $request)
    {
        $request->validate([
            'cpf' => 'required|string|min:11',
            'senha' => 'required|string',
        ]);

        $etapa1Ok = $this->loginEleicaoService->verificarCpfSenha($request->cpf, $request->senha);

        if (!$etapa1Ok) {
            return back()->with('error', 'CPF ou senha inválidos.')->withInput();
        }

        if($etapa1Ok->status === 0) {
            return back()->with('error', 'Seu cadastro está inativo. Entre em contato com o suporte.')->withInput();
        }

        $config = $this->loginEleicaoService->getDadosLoginEleicao();

        if ($config['configuracoes'] && $config['configuracoes']->autenticacao_de_2_etapas) {
            session(['eleitor_temp_id' => $etapa1Ok->id]);
            return redirect()->route('loginEleicao.segundaEtapa');
        }

        $token = Str::uuid()->toString();
        $etapa1Ok->update([
            'session_token_front' => $token,
            'ip' => request()->ip(),
        ]);

        session([
            'eleitor_id' => $etapa1Ok->id,
            'eleitor_nome' => $etapa1Ok->nome,
            'front_logado' => true,
            'ultima_atividade_front' => now()->timestamp,
            'session_token_front' => $token,
        ]);

        if($etapa1Ok->aceitou_os_termos === 0) {
            return redirect()->route('loginEleicao.termos')->with('success', 'Autenticação concluída!');
        } else {
            return redirect()->route('loginEleicao.homeLogadoFront')->with('success', 'Autenticação concluída!');
        }
    }

    public function segundaEtapa()
    {
        if (!session()->has('eleitor_temp_id')) {
            return redirect()->route('loginEleicao.index');
        }

        $dados = $this->loginEleicaoService->getDadosLoginEleicao();
        return view('loginEleicao.segundaEtapa', compact('dados'));
    }

    public function validarSegundaEtapa(Request $request)
    {
        $request->validate([
            'data_nascimento' => 'required|date',
            'celular' => 'required|string|min:11',
        ]);

        $idEleitor = session('eleitor_temp_id');

        $autenticado = $this->loginEleicaoService->verificarSegundaEtapa(
            $idEleitor,
            $request->data_nascimento,
            $request->celular
        );

        if (!$autenticado) {
            return back()->with('error', 'Dados não conferem.')->withInput();
        }

        $token = Str::uuid()->toString();
        $autenticado->update([
            'session_token_front' => $token,
            'ip' => request()->ip(),
        ]);

        session([
            'eleitor_id' => $autenticado->id,
            'eleitor_nome' => $autenticado->nome,
            'front_logado' => true,
            'ultima_atividade_front' => now()->timestamp,
            'session_token_front' => $token,
        ]);

        session()->forget('eleitor_temp_id');

        if($autenticado->aceitou_os_termos === 0) {
            return redirect()->route('loginEleicao.termos')->with('success', 'Autenticação concluída!');
        } else {
            return redirect()->route('loginEleicao.homeLogadoFront')->with('success', 'Autenticação concluída!');
        }
    }

    public function termos(Request $request)
    {
        $dados = $this->loginEleicaoService->getDadosLoginEleicao();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }
        return view('loginEleicao.termos', compact('dados'));
    }

    public function aceitarTermos(Request $request)
    {
        if($request->aceitarTermos == null) {
            $eleitorDeslogou = $this->loginEleicaoService->deslogaEleitor();

            session()->forget([
                'eleitor_id',
                'eleitor_nome',
                'front_logado',
                'eleitor_temp_id',
                'ultima_atividade_front',
                'session_token_front',
            ]);

            // Destroi a sessão completamente (opcional, mas recomendado)
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();

            return redirect()->route('home.index')->with('success', 'Você saiu da votação com sucesso.');
        }

        $request->merge([
            'aceitarTermos' => $request->has('aceitarTermos'),
        ]);

        $parametros = $request->validate([
            'eleitor_id' => 'nullable|string|max:100',
            'aceitarTermos' => 'required|boolean',
        ]);

        $this->loginEleicaoService->aceitaTermos($parametros);

        $dados = $this->loginEleicaoService->getDadosLoginEleicao();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }

        $config = $this->loginEleicaoService->getDadosLoginEleicao();

        if($config['configuracoes']->trocar_de_senha_depois_login === 1) {
            return redirect()->route('loginEleicao.trocarSenhaAposLogin');
        } else {
            return view('loginEleicao.homeLogadoFront', compact('dados'));
        }
    }

    public function trocarSenhaAposLogin(Request $request)
    {
        $dados = $this->loginEleicaoService->getDadosLoginEleicao();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }
        return view('loginEleicao.trocarSenhaAposLogin', compact('dados'));
    }

    public function senhaTrocarAposLogin(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required|string|min:6',
            'nova_senha' => 'required|string|min:8|confirmed',
        ], [
            'senha_atual.required' => 'Informe sua senha atual.',
            'senha_atual.min' => 'A senha atual deve ter pelo menos 6 caracteres.',
            'nova_senha.required' => 'Informe a nova senha.',
            'nova_senha.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
            'nova_senha.confirmed' => 'A confirmação da senha não confere.',
        ]);

        $resultado = $this->loginEleicaoService->trocarSenhaDepoisLogin($request->all());

        if ($resultado['status'] === 'success') {
            return redirect()->route('loginEleicao.homeLogadoFront');
        }

        return redirect()->back()->with('error', $resultado['mensagem']);
    }

    public function dadosEleitor()
    {
        if (!session('front_logado') || !session('eleitor_id')) {
            return response()->json(['error' => 'Não autenticado.'], 401);
        }

        $eleitorId = session('eleitor_id');

        $dados = $this->loginEleicaoService->buscarDadosEleitor($eleitorId);

        return response()->json($dados);
    }

    public function homeLogadoFront(Request $request)
    {
        $dados = $this->loginEleicaoService->getDadosLoginEleicao();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }

        return view('loginEleicao.homeLogadoFront', compact('dados'));
    }

    public function logout(Request $request)
    {
        $eleitorDeslogou = $this->loginEleicaoService->deslogaEleitor();

        session()->forget([
            'eleitor_id',
            'eleitor_nome',
            'front_logado',
            'eleitor_temp_id',
            'ultima_atividade_front',
            'session_token_front',
        ]);

        // Destroi a sessão completamente (opcional, mas recomendado)
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return redirect()->route('home.index')->with('success', 'Você saiu da votação com sucesso.');
    }
}