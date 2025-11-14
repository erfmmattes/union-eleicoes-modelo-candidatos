<?php

namespace App\Repositories\Admin;

use App\Models\EtapaCandidato;
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

    public function find(int $id): EtapaCandidato
    {
        return $this->model->findOrFail($id);
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
        return $etapa->delete();
    }
}