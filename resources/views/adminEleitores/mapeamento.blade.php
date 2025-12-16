@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Mapear colunas | Importar Eleitores')

@section('content')
<div class="container">
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Mapear Colunas</h1>
        <p class="text-muted mt-1">Associe as colunas do arquivo aos campos do banco de dados.</p>
    </div>

    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.adminEleitores.importarProcessar') }}" method="POST" id="formMapeamento">
                @csrf
                @method('POST')
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                @foreach($cabecalhos as $cabecalho)
                                    <th>{{ $cabecalho }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($cabecalhos as $index => $cabecalho)
                                    <th>
                                        <select name="mapeamento[{{ $index }}]" class="form-select select-mapeamento">
                                            <option value="">-- Ignorar --</option>
                                            @foreach($camposBanco as $campo)
                                                <option value="{{ $campo }}">{{ ucfirst($campo) }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                @endforeach

                                <input type="hidden" name="arquivoTemp" value="{{ $arquivoTemp }}">
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($linhas as $linha)
                                <tr>
                                    @foreach($linha as $valor)
                                        <td>{{ $valor }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.adminEleitores.importar') }}" class="btn bot-cancelar-importar px-4">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                    <button type="submit" id="botaoImportar" class="btn bot-atualizar px-4" disabled>
                        <i class="fa-solid fa-file-import me-1"></i> Confirmar Importação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<!---------------- Início - Estilos CSS -------------------->
<style>
    .bot-atualizar {
        background: linear-gradient(135deg, #183F77, #4A90E2);
        color: #fff !important;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .bot-atualizar:hover {
        background: linear-gradient(135deg, #122b55, #3570c2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
    .bot-cancelar-importar {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
    }
    .bot-cancelar-importar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->
<!---------------- Início - Script -------------------->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('.select-mapeamento');
    const botao = document.getElementById('botaoImportar');
    
    // Campos obrigatórios que precisam estar selecionados
    const camposObrigatorios = ['nome', 'email', 'cpf_cnpj', 'celular'];

    function verificarObrigatorios() {
        // Captura os valores selecionados em todos os selects
        const valoresSelecionados = Array.from(selects).map(s => s.value);

        // Verifica se todos os obrigatórios estão entre os selecionados
        const todosPresentes = camposObrigatorios.every(campo =>
            valoresSelecionados.includes(campo)
        );

        botao.disabled = !todosPresentes;
    }

    selects.forEach(select => {
        select.addEventListener('change', verificarObrigatorios);
    });

    // Verifica no carregamento inicial
    verificarObrigatorios();
});
</script>
<!---------------- Final - Script -------------------->