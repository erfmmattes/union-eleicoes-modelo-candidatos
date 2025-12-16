@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Importar Eleitores')

@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="mb-5">
            <h1 class="h2 fw-bolder text-dark">Importar Eleitores</h1>
            <p class="text-muted mt-1">
                Faça upload de arquivos XLS ou CSV para cadastrar eleitores em massa. O mapeamento será automático.
            </p>
        </div>

        <!-- Card de Importação -->
        <div class="card shadow-lg border-0 rounded-3 stat-card">
            <div class="card-body p-4 p-md-5">

                <!-- Alertas de sucesso ou erro -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                @endif

                <!-- Formulário de Upload -->
                <form id="formImportar" action="{{ route('admin.adminEleitores.preVisualizar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="arquivo" class="form-label fw-semibold">Selecione o arquivo <strong>*</strong></label>
                        <input type="file" name="arquivo" id="arquivo" class="form-control" accept=".csv,.xls,.xlsx" required>
                        <small class="text-muted">Formatos suportados: CSV, XLS, XLSX</small>
                        @error('arquivo')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </form>

                <!-- Observações -->
                <div class="mt-4 text-muted small">
                    <p>Certifique-se de que o arquivo contém as colunas obrigatórias: <strong>Nome, E-mail, CPF/CNPJ, Celular</strong>.</p>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.adminEleitores.index') }}" class="btn bot-cancelar-importar px-4">
                        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                    </a>
                </div>

            </div>
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
<!---------------- Início - Scripts JavaScript -------------------->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-temporaria');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 10000);
    });
    const arquivoInput = document.getElementById('arquivo');
    arquivoInput.addEventListener('change', function() {
        const form = document.getElementById('formImportar');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        .then(response => response.text())
        .then(html => {
            document.open();
            document.write(html);
            document.close();
        })
        .catch(err => alert('Erro ao enviar o arquivo.'));
    });
});
</script>
<!---------------- Final - Scripts JavaScript -------------------->