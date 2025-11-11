<?php

namespace App\Exports;

use App\Repositories\Admin\ListaEleitoresRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class ListaDeEleitoresEcxelExport implements FromCollection, WithHeadings
{
    protected ?string $busca;
    protected array $campos;
    protected ListaEleitoresRepository $listaEleitoresRepository;

    public function __construct(
        ListaEleitoresRepository $listaEleitoresRepository,
        ?string $busca = null,
        array $campos = ['id','nome','cpf_cnpj','email','celular', 'passou_por_ajuste', 'recuperacao_senha', 'troca_senha', 'status', 'created_at', 'updated_at']
    ) {
        $this->listaEleitoresRepository = $listaEleitoresRepository;
        $this->busca = $busca;
        $this->campos = $campos;
    }

    public function collection()
    {
        $listaDeEleitores = $this->listaEleitoresRepository->listarTodosSemPaginacao(['q' => $this->busca]);

        return $listaDeEleitores->map(function ($eleitor) {
            $data = [];

            foreach ($this->campos as $campo) {
                switch ($campo) {
                    case 'cpf_cnpj':
                        $data[$campo] = $eleitor->cpf_cnpj
                            ? formatarCpfCnpj($eleitor->cpf_cnpj)
                            : '-';
                        break;

                    case 'celular':
                        $data[$campo] = $eleitor->celular
                            ? formatarTelefone($eleitor->celular)
                            : '-';
                        break;

                    case 'created_at':
                        $data[$campo] = $eleitor->created_at
                            ? Carbon::parse($eleitor->created_at)->format('d/m/Y H:i')
                            : '-';
                        break;

                    case 'updated_at':
                        $data[$campo] = $eleitor->updated_at
                            ? Carbon::parse($eleitor->updated_at)->format('d/m/Y H:i')
                            : '-';
                        break;

                    case 'passou_por_ajuste':
                        $data[$campo] = $eleitor->passou_por_ajuste ? 'Sim' : 'Não';
                        break;

                    case 'recuperacao_senha':
                        if ($eleitor->quantidade_recuperacao_senha > 0) {
                            $data[$campo] = 'Sim (' . $eleitor->quantidade_recuperacao_senha . ')';
                        } else {
                            $data[$campo] = 'Não';
                        }
                        break;

                    case 'troca_senha':
                        if ($eleitor->quantidade_troca_senha > 0) {
                            $data[$campo] = 'Sim (' . $eleitor->quantidade_troca_senha . ')';
                        } else {
                            $data[$campo] = 'Não';
                        }
                        break;

                    case 'status':
                        $data[$campo] = $eleitor->status ? 'Ativo' : 'Inativo';
                        break;

                    default:
                        $data[$campo] = $eleitor->{$campo} ?? '-';
                        break;
                }
            }

            return $data;
        });
    }

    public function headings(): array
    {
        // 🔹 Mapeia os rótulos dinamicamente de acordo com os campos selecionados
        $labels = [
            'id' => 'ID',
            'nome' => 'Nome',
            'cpf_cnpj' => 'CPF/CNPJ',
            'email' => 'E-mail',
            'celular' => 'Celular',
            'passou_por_ajuste' => 'Passou por Ajuste',
            'status' => 'Status',
            'created_at' => 'Data de Criação',
            'updated_at' => 'Última Atualização',
        ];

        return array_map(fn($campo) => $labels[$campo] ?? ucfirst(str_replace('_', ' ', $campo)), $this->campos);
    }
}