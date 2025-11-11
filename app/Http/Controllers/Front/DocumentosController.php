<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\DocumentosService;

class DocumentosController extends Controller
{
    protected $documentosService;

    public function __construct(DocumentosService $documentosService)
    {
        $this->documentosService = $documentosService;
    }

    public function index()
    {
        $dados = $this->documentosService->getDadosDocumentos();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }
        $documentos = $this->documentosService->listarDocumentos();
        return view('documentos.index', compact('dados', 'documentos'));
    }

    public function show($id)
    {
        $documento = $this->documentosService->buscarDocumento($id);
        return view('documentos.show', compact('documento'));
    }
}