<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\Eleitor;
use App\Models\Voto;

class HomeAdminRepository
{
    public function obterUsuariosAtivos(): int
    {
        return Eleitor::where('status', 1)->count();
    }

    public function totalVotantes(): int
    {
        return Eleitor::where('votou', '=', '1')->where('status', 1)->count();
    }

    public function totalNaoVotantes(): int
    {
        return Eleitor::where('votou', '=', '0')->where('status', 1)->count();
    }

    public function totalVotantesPorDia()
    {
        return Voto::select(
            DB::raw('DATE(votado_em) as data'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('data')
        ->orderBy('data')
        ->get()
        ->mapWithKeys(fn($item) => [date('d/m/y', strtotime($item->data)) => $item->total])
        ->toArray();
    }
}