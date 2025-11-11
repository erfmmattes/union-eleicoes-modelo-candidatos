<?php

namespace App\Repositories\Admin;

use App\Models\Documento;

class DocumentosRepository
{
    protected Documento $documento;

    public function __construct()
    {
        $this->documento = new Documento();
    }
    
    public function listarComFiltro(array $filtros = [])
    {
        $query = $this->documento->query();

        if (!empty($filtros['q'])) {
            $query->where(function($q) use ($filtros) {
                $q->where('titulo', 'like', '%' . $filtros['q'] . '%')
                  ->orWhere('descricao', 'like', '%' . $filtros['q'] . '%');
            });
        }

        if (!empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        if (isset($filtros['ativo']) && $filtros['ativo'] !== '') {
            $query->where('ativo', $filtros['ativo']);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public function atualizarStatus(int $id, array $dados): bool
    {
        $documento = Documento::findOrFail($id);
        return $documento->update($dados);
    }

    public function buscarPorId(int $id)
    {
        return Documento::findOrFail($id);
    }

    public function criar(array $dados)
    {
        return Documento::create($dados);
    }

    public function atualizar(int $id, array $dados)
    {
        $documento = Documento::findOrFail($id);
        $documento->update($dados);
        return $documento;
    }

    public function excluir(int $id)
    {
        return Documento::destroy($id);
    }
}