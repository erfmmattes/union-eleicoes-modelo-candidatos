<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DeclaracaoEleicaoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeclaracaoEleicaoController extends Controller
{
    protected DeclaracaoEleicaoService $declaracaoEleicaoService;

    public function __construct(DeclaracaoEleicaoService $declaracaoEleicaoService)
    {
        $this->middleware('auth');
        $this->declaracaoEleicaoService = $declaracaoEleicaoService;
    }

    public function gerarPdf(Request $request)
    {
        return $this->declaracaoEleicaoService->gerarDeclaracaoPdf($request->all());
    }
}