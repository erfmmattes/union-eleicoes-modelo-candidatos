<?php

namespace App\Exports;

use App\Repositories\Admin\VotanteRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class VotanteEcxelExport implements FromCollection, WithHeadings
{
    protected $busca;
    protected $campos;
    protected VotanteRepository $votanteRepository;

    public function __construct(VotanteRepository $votanteRepository, ?string $busca = null, ?string $etapa = null, array $campos = ['id','nome','cpf_cnpj','votado_em','votou'])
    {
        $this->votanteRepository = $votanteRepository;
        $this->busca = $busca;
        $this->etapa = $etapa;
        $this->campos = $campos;
    }

    public function collection()
    {
        $votantes = $this->votanteRepository->listarTodosSemPaginacaoComBusca($this->busca, $this->etapa);

        return $votantes->map(function($votante) {
            $data = [];

            foreach($this->campos as $campo) {
                if($campo === 'votado_em') {
                    $data[$campo] = $votante->votado_em 
                        ? Carbon::parse($votante->votado_em)->format('d/m/Y H:i') 
                        : '-';
                } elseif($campo === 'votou') {
                    $data[$campo] = $votante->votou == '1' ? 'Sim' : 'Não';
                } elseif($campo === 'cpf_cnpj') {
                    $data[$campo] = formatarCpfCnpj($votante->cpf_cnpj);
                } else {
                    $data[$campo] = $votante->$campo ?? '-';
                }
            }

            return $data;
        });
    }

    public function headings(): array
    {
        return $this->campos;
    }
}