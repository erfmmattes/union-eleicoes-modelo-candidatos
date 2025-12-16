<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\Front\VotarService;
use App\Services\Front\ComprovanteService;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class VotarController extends Controller
{
    protected $votarService;
    protected $comprovanteService;

    public function __construct(VotarService $votarService, ComprovanteService $comprovanteService)
    {
        // $this->middleware(['sessao.unica.front', 'periodo.eleicao', 'verifica.troca.senha']);
        $this->votarService = $votarService;
        $this->comprovanteService = $comprovanteService;
    }

    public function index()
    {
        $dados = $this->votarService->getDadosLoginEleicao();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }

        $eleitorId = session('eleitor_id');

        // lista todas as etapas
        $listaEtapas = $this->votarService->listaEtapasVotar();

        if (!$listaEtapas || $listaEtapas->count() === 0) {
            return view('votar.index', [
                'dados' => $dados,
                'listaEtapas' => collect([]),
                'etapaAtual' => null
            ]);
        }

        // Encontrar a primeira etapa que o eleitor AINDA NÃO VOTOU
        $etapaAtual = $listaEtapas->first(function ($etapa) use ($eleitorId) {
            $slug = "etapa_" . $etapa->sequencia;
            return !$this->votarService->jaVotou($eleitorId, $slug);
        });

        // Se votou todas as etapas → finalizar votação
        if (!$etapaAtual) {
            $listaComprovantes = $this->comprovanteService->getComprovanteEleitor();
            return view('comprovante.index', [
                'dados' => $dados,
                'listaEtapas' => $listaEtapas,
                'listaComprovantes' => $listaComprovantes,
                'etapaAtual' => null
            ]);
        }

        return view('votar.index', compact('dados','listaEtapas','etapaAtual'));
    }

    public function salvarEtapa(Request $request)
    {
        $eleitorId = session('eleitor_id');

        if (!$eleitorId) {
            return back()->with('error', 'Sessão expirada. Faça login novamente.');
        }

        $data = [
            'eleitor_id' => $eleitorId,
            'etapa_id'   => $request->etapa_id,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        if ($request->has('escolhas')) $data['escolhas'] = $request->escolhas;
        if ($request->has('escolha'))  $data['escolha']  = $request->escolha;

        try {

            $proxima = $this->votarService->registrarVoto($data);

            // Se existe próxima etapa → redireciona para ela
            if ($proxima) {
                return redirect()->route('votar.index')
                    ->with('success', 'Voto computado com sucesso!');
            }

            // Senão, finalizou
            return redirect()->route('votar.fim')
                ->with('success', 'Voto computado com sucesso!');

        } catch (ValidationException $ve) {
            return back()->withErrors($ve->errors())->withInput();

        } catch (\Throwable $e) {
            return back()->with('error', 'Erro ao registrar o voto.')->withInput();
        }
    }
}