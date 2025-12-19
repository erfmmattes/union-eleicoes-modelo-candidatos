@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Apuração')

@section('content')
<div class="container">

    <!-- Cabeçalho -->
    <div class="mb-4">
        <h1 class="h2 fw-bolder text-dark">Apuração da Eleição por Etapa</h1>
        <p class="text-muted mt-1">
            Clique em uma etapa para visualizar os detalhes da apuração.
        </p>
    </div>

    <!-- Lista -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-3 p-md-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light bod-tabled">
                        <tr>
                            <th>ID</th>
                            <th>Etapa</th>
                            <th class="text-center">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($etapas as $etapa)
                            <tr>
                                <td>{{ $etapa->id }}</td>
                                <td>{{ $etapa->nome }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.adminApuracao.show', $etapa->id) }}" title="Ver Apuração"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.adminApuracao.apuracaoTotalPdf', $etapa->id) }}" title="Gerar PDF"
                                       class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Nenhuma etapa encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
