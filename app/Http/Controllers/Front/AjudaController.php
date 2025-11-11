<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\AjudaService;

class AjudaController extends Controller
{
    protected $ajudaService;

    public function __construct(AjudaService $ajudaService)
    {
        $this->ajudaService = $ajudaService;
    }

    public function index()
    {
        $dados = $this->ajudaService->getConfiguracoesAjuda();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }
        $ajudas = $this->ajudaService->getAjudaPrincipal();
        return view('ajuda.index', compact('dados', 'ajudas'));
    }
}