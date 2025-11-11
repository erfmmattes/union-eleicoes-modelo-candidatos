@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Dados da Eleição')

@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="mb-5 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 fw-bolder text-dark">Dados da Eleição</h1>
                <p class="text-muted mt-1">
                    Resumo geral das informações e status da eleição.
                </p>
            </div>
            <div class="d-flex">
                <button type="button" class="btn botao-geral" title="Baixar PDF" data-bs-toggle="modal" data-bs-target="#dadosEle">
                    <i class="fa-solid fa-file-pdf me-2"></i>
                </button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="dadosEle" tabindex="-1" aria-labelledby="modalPdfLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header text-white fund-cont">
                        <h5 class="modal-title m-auto" id="modalPdfLabel">Gerar PDF - Dados da Eleição</h5>
                    </div>
                    <div>
                        <form id="pFormGerarPdf" target="_blank" action="{{ route('admin.adminDadosEleicao.pdf') }}" method="POST">
                            @csrf

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="orientacao" class="form-label fw-semibold">Orientação:</label>
                                    <select class="form-select" name="orientacao" id="orientacao" required>
                                        <option value="portrait" selected>Retrato (vertical)</option>
                                        <option value="landscape">Paisagem (horizontal)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="nome_arquivo" class="form-label fw-semibold">Nome do arquivo:</label>
                                    <input type="text" class="form-control" id="nome_arquivo" name="nome_arquivo"
                                        placeholder="Ex: dados_da_eleicao">
                                </div>
                            </div>

                            <div class="modal-footer d-flex justify-content-center">
                                <button type="button" class="btn px-4 bot-cancelar me-2" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn px-4 bot-confirmar">
                                    Gerar PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card principal -->
        <div class="card shadow-lg border-0 rounded-3 stat-card">
            <div class="card-body p-3 p-md-5">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light bod-tabled">
                            <tr class="text-center">
                                <th>Eleitores</th>
                                <th>Senhas Geradas</th>
                                <th>Emails Enviados</th>
                                <th>Telefones</th>
                                <th>SMS Enviados</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>
                                    <strong title="Quantidade">{{ $dados['total_eleitores'] }}</strong><br>
                                    @if($dados['statusEleicao']->total_eleitores == 1)
                                        <span class="text-secondary small" title="Status">Concluído</span>
                                    @else
                                        <span class="text-secondary small" title="Status">Pendente</span>
                                    @endif
                                </td>
                                <td>
                                    <strong title="Quantidade">{{ $dados['senhas_geradas'] }}</strong><br>
                                    @if($dados['statusEleicao']->senhas_geradas == 1)
                                        <span class="text-secondary small" title="Status">Concluído</span>
                                    @else
                                        <span class="text-secondary small" title="Status">Pendente</span>
                                    @endif
                                </td>
                                <td>
                                    <strong title="Quantidade">{{ $dados['emails_enviados'] }}</strong><br>
                                    @if($dados['statusEleicao']->emails_enviados == 1)
                                        <span class="text-secondary small" title="Status">Concluído</span>
                                    @else
                                        <span class="text-secondary small" title="Status">Pendente</span>
                                    @endif
                                </td>
                                <td>
                                    <strong title="Quantidade">{{ $dados['telefones'] }}</strong><br>
                                    @if($dados['statusEleicao']->telefones == 1)
                                        <span class="text-secondary small" title="Status">Concluído</span>
                                    @else
                                        <span class="text-secondary small" title="Status">Pendente</span>
                                    @endif
                                </td>
                                <td>
                                    <strong title="Quantidade">{{ $dados['sms_enviados'] }}</strong><br>
                                    @if($dados['statusEleicao']->sms_enviados == 1)
                                        <span class="text-secondary small" title="Status">Concluído</span>
                                    @else
                                        <span class="text-secondary small" title="Status">Pendente</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
<!---------------- Início - Estilos CSS -------------------->
<style>
.botao-geral {
    background: linear-gradient(135deg, #183F77, #4A90E2);
    color: #fff !important;
    height: 50px;
    display: flex !important;          
    align-items: center !important;   
    justify-content: center !important;
}
.botao-geral:hover {
    background: linear-gradient(135deg, #122b55, #3570c2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-weight: 600 !important;
}
.table th {
    font-weight: 600 !important;
}
.table td strong {
    font-size: 1rem;
}
</style>
<!---------------- Final - Estilos CSS -------------------->
<!---------------- Início - Scripts JavaScript -------------------->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('pFormGerarPdf');
        const modal = document.getElementById('dadosEle');

        form.addEventListener('submit', function () {
            // Fecha o modal
            const bsModal = bootstrap.Modal.getInstance(modal);
            if(bsModal) bsModal.hide();
        });
    });
</script>
<!---------------- Final - Scripts JavaScript -------------------->