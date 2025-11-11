<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\DadosEleicaoStatus;
use App\Models\Eleitor;

class DadosEleicaoRepository
{
    public function listaEleitores()
    {
        return Eleitor::all();
    }

    public function obterResumo()
    {
        $totalEleitores = Eleitor::count();
        $senhasGeradas = Eleitor::whereNotNull('senha')->where('senha', '!=', '')->count();
        $emailsEnviados = Eleitor::where('enviou_senha_email', '=', '1')->count();
        $telefones = Eleitor::whereNotNull('celular')->where('celular', '!=', '')->count();
        $smsEnviados = Eleitor::where('enviou_senha_sms', '=', '1')->count();
        $statusEleicao = DadosEleicaoStatus::find(1);
        // dd($statusEleicao->emails_enviados);

        return [
            'status' => 'Concluído',
            'total_eleitores' => $totalEleitores,
            'senhas_geradas' => $senhasGeradas,
            'emails_enviados' => $emailsEnviados,
            'telefones' => $telefones,
            'sms_enviados' => $smsEnviados,
            'statusEleicao' => $statusEleicao,
        ];
    }
}