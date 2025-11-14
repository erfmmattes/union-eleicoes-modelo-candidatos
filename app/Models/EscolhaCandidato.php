<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscolhaCandidato extends Model
{
    use HasFactory;

    // 🔗 Nome da tabela
    protected $table = 'escolhas_candidatos';

    // 🧱 Campos que podem ser preenchidos em massa
    protected $fillable = [
        'etapas_candidatos_id',
        'titulo',
        'nome',
        'cargo',
        'tem_foto',
        'foto',
        'caminho',
        'sequencia',
        'status',
    ];

    // 🕒 Controla automaticamente created_at / updated_at
    public $timestamps = true;

    // 🎛️ Casts automáticos
    protected $casts = [
        'tem_foto' => 'boolean',
        'status' => 'boolean',
    ];

    // 🔗 Relacionamento: uma escolha pertence a uma etapa
    public function etapa()
    {
        return $this->belongsTo(EtapaCandidato::class, 'etapas_candidatos_id');
    }
}