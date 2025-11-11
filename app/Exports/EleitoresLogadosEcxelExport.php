<?php

namespace App\Exports;

use App\Repositories\Admin\EleitorLogadoRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class EleitoresLogadosEcxelExport implements FromCollection, WithHeadings
{
    protected ?string $busca;
    protected array $campos;
    protected EleitorLogadoRepository $eleitorLogadoRepository;

    public function __construct(
        EleitorLogadoRepository $eleitorLogadoRepository,
        ?string $busca = null,
        array $campos = ['id','nome','cpf_cnpj','email','celular', 'ip', 'created_at']
    ) {
        $this->eleitorLogadoRepository = $eleitorLogadoRepository;
        $this->busca = $busca;
        $this->campos = $campos;
    }

    public function collection()
    {
        $eleitoresLogados = $this->eleitorLogadoRepository->listarTodosSemPaginacao(['q' => $this->busca]);

        return $eleitoresLogados->map(function ($eleitoreLogado) {
            $data = [];

            foreach ($this->campos as $campo) {
                switch ($campo) {
                    case 'id':
                        $data[$campo] = $eleitoreLogado->id
                            ? $eleitoreLogado->eleitore_logado_id
                            : '-';
                        break;

                    case 'cpf_cnpj':
                        $data[$campo] = $eleitoreLogado->cpf_cnpj
                            ? formatarCpfCnpj($eleitoreLogado->cpf_cnpj)
                            : '-';
                        break;

                    case 'celular':
                        $data[$campo] = $eleitoreLogado->celular
                            ? formatarTelefone($eleitoreLogado->celular)
                            : '-';
                        break;

                    case 'ip':
                        $data[$campo] = $eleitoreLogado->eleitore_logado_ip
                            ? $eleitoreLogado->eleitore_logado_ip
                            : '-';
                        break;

                    case 'created_at':
                        $data[$campo] = $eleitoreLogado->eleitore_logado_created_at
                            ? Carbon::parse($eleitoreLogado->eleitore_logado_created_at)->format('d/m/Y H:i')
                            : '-';
                        break;

                    default:
                        $data[$campo] = $eleitoreLogado->{$campo} ?? '-';
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
            'ip' => 'IP',
            'created_at' => 'Data e Horário do Login',
        ];

        return array_map(fn($campo) => $labels[$campo] ?? ucfirst(str_replace('_', ' ', $campo)), $this->campos);
    }
}