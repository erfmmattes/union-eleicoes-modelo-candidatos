<?php

namespace App\Exports;

use App\Repositories\Admin\NaoVotanteRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class NaoVotanteEcxelExport implements FromCollection, WithHeadings
{
    protected $busca;
    protected $campos;
    protected NaoVotanteRepository $naoVotanteRepository;

    public function __construct(NaoVotanteRepository $naoVotanteRepository, ?string $busca = null, array $campos = ['id','nome','cpf_cnpj','celular','email','votado_em','votou'])
    {
        $this->naoVotanteRepository = $naoVotanteRepository;
        $this->busca = $busca;
        $this->campos = $campos;
    }

    public function collection()
    {
        $votantes = $this->naoVotanteRepository->listarTodosSemPaginacaoComBusca($this->busca);

        return $votantes->map(function($votante) {
            $data = [];

            foreach($this->campos as $campo) {
                if($campo === 'votou') {
                    $data[$campo] = $votante->votou == '1' ? 'Sim' : 'Não';
                } elseif($campo === 'cpf_cnpj') {
                    $data[$campo] = formatarCpfCnpj($votante->cpf_cnpj);
                } elseif($campo === 'celular') {
                    $data[$campo] = formatarTelefone($votante->celular);
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