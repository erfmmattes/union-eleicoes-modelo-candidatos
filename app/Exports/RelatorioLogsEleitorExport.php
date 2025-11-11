<?php

namespace App\Exports;

use App\Models\RelatorioLogEleitor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RelatorioLogsEleitorExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return RelatorioLogEleitor::select(
            'eleitor_id',
            'eleitor_nome',
            'acao',
            'mensagem',
            'ip',
            'pagina',
            'created_at'
        )->get()->map(function($log) {
            return [
                'eleitor_id'   => $log->eleitor_id,
                'eleitor_nome' => $log->eleitor_nome,
                'acao'         => $log->acao,
                'mensagem'     => $log->mensagem ? preg_replace('/\s+/', ' ', $log->mensagem) : '-',
                'ip'           => $log->ip ?? '-',
                'pagina'       => $log->pagina ?? '-',
                'created_at'   => $log->created_at ? $log->created_at->format('d/m/Y H:i') : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID do Eleitor',
            'Nome do Eleitor',
            'Ação',
            'Mensagem',
            'Endereço IP',
            'Página',
            'Data/Hora'
        ];
    }
}