<?php

namespace App\Repositories\Admin;

use App\Models\EtapaCandidato;
use App\Models\Setor;
use Illuminate\Database\Eloquent\Collection;

class EtapaCandidatoRepository
{
    protected EtapaCandidato $model;

    public function __construct(EtapaCandidato $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 10)
    {
        return $this->model->orderBy('sequencia')->paginate($perPage);
    }

    public function all()
    {
        return $this->model->orderBy('sequencia')->get();
    }

    public function setoresAll()
    {
        return Setor::where('status', '=', '1')->orderBy('nome', 'asc')->get();
    }

    public function find(int $id): EtapaCandidato
    {
        return $this->model->findOrFail($id);
    }

    public function etapasRelacionadas(int $id)
    {
        return $this->model
        ->with('escolhas')
        ->where('id', $id)
        ->get();
    }

    public function create(array $data): EtapaCandidato
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): EtapaCandidato
    {
        $etapa = $this->find($id);
        $etapa->update($data);
        return $etapa;
    }

    public function delete(int $id): bool
    {
        $etapa = $this->find($id);
        if ($etapa->escolhas()->exists()) {
            return false;
        }

        return $etapa->delete();
    }
}