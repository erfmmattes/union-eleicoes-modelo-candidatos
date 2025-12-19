<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapaCandidato extends Model
{
    use HasFactory;

    protected $table = 'etapas_candidatos';

    protected $fillable = [
        'nome',
        'setores_id',
        'multipla_escolha',
        'quantidade_minima_escolhas',
        'quantidade_maxima_escolhas',
        'sequencia',
        'status',
    ];

    public $timestamps = true;

    protected $casts = [
        // 'status' => 'boolean',
    ];

    public function escolhas()
    {
        return $this->hasMany(EscolhaCandidato::class, 'etapas_candidatos_id', 'id');
    }

    public function setor()
    {
        return $this->belongsTo(Setor::class, 'setores_id');
    }
}