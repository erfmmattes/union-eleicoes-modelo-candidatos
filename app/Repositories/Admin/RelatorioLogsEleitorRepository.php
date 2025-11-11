<?php

namespace App\Repositories\Admin;

use App\Models\RelatorioLogEleitor;

class RelatorioLogsEleitorRepository
{
    public function listar(?string $search = null)
    {
        $query = RelatorioLogEleitor::query();

        if ($search) {
            $query->where('acao', 'like', "%{$search}%")
                  ->orWhere('ip', 'like', "%{$search}%")
                  ->orWhere('eleitor_nome', 'like', "%{$search}%")
                  ->orWhere('pagina', 'like', "%{$search}%")
                  ->orWhere('mensagem', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function criarLog(string $eleitor_id, string $eleitor_nome, string $acao, string $mensagem, string $ip, string $pagina)
    {
        return RelatorioLogEleitor::create([
            'eleitor_id' => $eleitor_id,
            'eleitor_nome' => $eleitor_nome,
            'acao' => $acao,
            'mensagem' => $mensagem,
            'ip' => $ip,
            'pagina' => $pagina,
        ]);
    }

    public function buscarPorId(int $id)
    {
        return RelatorioLogEleitor::find($id);
    }

    public function excluir(int $id)
    {
        return RelatorioLogEleitor::destroy($id);
    }

    public function obterTodos()
    {
        return RelatorioLogEleitor::orderBy('created_at', 'desc')->get();
    }
}