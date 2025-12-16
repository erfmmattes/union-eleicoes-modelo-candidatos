<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Front\ComprovanteService;

class ComprovanteController extends Controller
{
    protected $comprovanteService;

    public function __construct(ComprovanteService $comprovanteService)
    {
        $this->comprovanteService = $comprovanteService;
    }

    public function index()
    {
        $dados = $this->comprovanteService->getConfiguracoesComprovante();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }
        $listaComprovantes = $this->comprovanteService->getComprovanteEleitor();

        if (!$listaComprovantes) {
            return redirect()
                ->route('loginEleicao.homeLogadoFront')
                ->with('error', 'Nenhum comprovante disponível para este eleitor.');
        }

        return view('comprovante.index', compact('dados', 'listaComprovantes'));
    }

    public function receberPorEmail(Request $request)
    {
        $receberPorEmailComprovante = $this->comprovanteService->receberPorEmailComprovanteEleicao();

        return redirect()->route('comprovante.index')->with('success', 'Comprovante enviado com sucesso!');
    }

    public function baixarPdfComprovante(Request $request)
    {
        $baixarPdfComprovante = $this->comprovanteService->baixarPdfComprovanteEleicao();
        return $baixarPdfComprovante;

        return redirect()->route('comprovante.index')->with('success', 'Comprovante enviado com sucesso!');
    }
}