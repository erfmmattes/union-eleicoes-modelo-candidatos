<?php

namespace App\Repositories\Admin;

use App\Models\Setor;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SetoresRepository
{
    protected Setor $model;

    public function __construct(Setor $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->orderBy('nome')
            ->paginate($perPage);
    }

    public function all(): Collection
    {
        return $this->model
            ->orderBy('nome')
            ->get();
    }

    public function find(int $id): Setor
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Setor
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Setor
    {
        $setor = $this->find($id);
        $setor->update($data);

        return $setor;
    }

    public function delete(int $id): bool
    {
        $setor = $this->find($id);

        if ($setor->etapas()->exists()) {
            return false;
        }

        return $setor->delete();
    }
}