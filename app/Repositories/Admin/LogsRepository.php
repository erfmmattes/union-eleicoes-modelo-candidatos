<?php

namespace App\Repositories\Admin;

use App\Models\Log;

class LogsRepository
{
    public function getAll(?string $search = null)
    {
        $query = Log::orderBy('created_at', 'desc');

        if ($search) {
            $query->where('nome_log', 'like', "%{$search}%")
                ->orWhere('mensagem', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function findById(int $id)
    {
        return Log::find($id);
    }

    public function deleteById(int $id)
    {
        $log = Log::find($id);

        if ($log) {
            return $log->delete();
        }

        return false;
    }
}