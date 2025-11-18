<?php

namespace App\Services\Admin;

use App\Repositories\Admin\EscolhaCandidatoRepository;
use Illuminate\Support\Facades\Storage;

class EscolhaCandidatoService
{
    protected EscolhaCandidatoRepository $repo;

    public function __construct(EscolhaCandidatoRepository $repo)
    {
        $this->repo = $repo;
    }

    public function listar($perPage = 10)
    {
        return $this->repo->paginate($perPage);
    }

    public function criar(array $data)
    {
        if (isset($data['foto_upload'])) {
            $path = $data['foto_upload']->store('escolhas', 'public');

            $data['tem_foto'] = true;
            $data['foto'] = $data['foto_upload']->getClientOriginalName();
            $data['caminho'] = $path;
        }

        return $this->repo->create($data);
    }

    public function listarTodasEtapas()
    {
        return $this->repo->etapasAll();
    }

    public function buscar($id)
    {
        return $this->repo->find($id);
    }

    public function atualizar($id, array $data)
    {
        $escolha = $this->repo->find($id);

        if (isset($data['foto_upload'])) {

            // Apagar foto antiga
            if ($escolha->caminho && Storage::disk('public')->exists($escolha->caminho)) {
                Storage::disk('public')->delete($escolha->caminho);
            }

            // Salvar nova foto
            $path = $data['foto_upload']->store('escolhas', 'public');

            $data['tem_foto'] = true;
            $data['foto'] = $data['foto_upload']->getClientOriginalName();
            $data['caminho'] = $path;
        }

        return $this->repo->update($id, $data);
    }

    public function deletar($id)
    {
        $escolha = $this->repo->find($id);

        // Deletar foto do storage
        if ($escolha->caminho && Storage::disk('public')->exists($escolha->caminho)) {
            Storage::disk('public')->delete($escolha->caminho);
        }

        return $this->repo->delete($id);
    }

    public function toggleStatus($id)
    {
        $item = $this->repo->find($id);
        $novoStatus = !$item->status;

        return $this->repo->update($id, ['status' => $novoStatus]);
    }
}