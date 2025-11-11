<?php

namespace App\Exports;

use App\Repositories\Admin\ListaChamadaEleitoresRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class ListaDeChamadaEcxelExport implements FromCollection, WithHeadings
{
    protected ?string $busca;
    protected array $campos;
    protected ListaChamadaEleitoresRepository $listaChamadaEleitoresRepository;

    public function __construct(
        ListaChamadaEleitoresRepository $listaChamadaEleitoresRepository,
        ?string $busca = null,
        array $campos = ['lista_chamada_id','nome','cpf_cnpj','email','celular', 'created_at']
    ) {
        $this->listaChamadaEleitoresRepository = $listaChamadaEleitoresRepository;
        $this->busca = $busca;
        $this->campos = $campos;
    }

    public function collection()
    {
        $listaDeChamadas = $this->listaChamadaEleitoresRepository->listarTodosSemPaginacao(['q' => $this->busca]);

        return $listaDeChamadas->map(function ($listaDeChamada) {
            $data = [];

            foreach ($this->campos as $campo) {
                switch ($campo) {
                    case 'id':
                        $data[$campo] = $listaDeChamada->id
                            ? $listaDeChamada->lista_chamada_id
                            : '-';
                        break;

                    case 'cpf_cnpj':
                        $data[$campo] = $listaDeChamada->cpf_cnpj
                            ? formatarCpfCnpj($listaDeChamada->cpf_cnpj)
                            : '-';
                        break;

                    case 'celular':
                        $data[$campo] = $listaDeChamada->celular
                            ? formatarTelefone($listaDeChamada->celular)
                            : '-';
                        break;

                    case 'created_at':
                        $data[$campo] = $listaDeChamada->created_at
                            ? Carbon::parse($listaDeChamada->created_at)->format('d/m/Y H:i')
                            : '-';
                        break;

                    default:
                        $data[$campo] = $listaDeChamada->{$campo} ?? '-';
                        break;
                }
            }

            return $data;
        });
    }

    public function headings(): array
    {
        $labels = [
            'id' => 'ID',
            'nome' => 'Nome',
            'cpf_cnpj' => 'CPF/CNPJ',
            'email' => 'E-mail',
            'celular' => 'Celular',
            'created_at' => 'Data e Horário',
        ];

        return array_map(fn($campo) => $labels[$campo] ?? ucfirst(str_replace('_', ' ', $campo)), $this->campos);
    }
}