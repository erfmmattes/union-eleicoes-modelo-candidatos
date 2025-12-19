<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscolhaCandidato extends Model
{
    use HasFactory;

    protected $table = 'escolhas_candidatos';

    protected $fillable = [
        'etapas_candidatos_id',
        'nome',
        'cargo',
        'tem_foto',
        'foto',
        'caminho',
        'sequencia',
        'branco_nulo_abstencao',
        'status',
    ];

    public $timestamps = true;

    protected $casts = [
        'tem_foto' => 'boolean',
        'status' => 'boolean',
    ];

    public function etapa()
    {
        return $this->belongsTo(EtapaCandidato::class, 'etapas_candidatos_id');
    }
}