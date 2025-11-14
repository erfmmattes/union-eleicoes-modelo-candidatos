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
        'sequencia',
        'status',
    ];

    public $timestamps = true;

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * 🔗 Relacionamento com as escolhas de candidatos
     * Uma etapa pode ter várias escolhas.
     */
    public function escolhas()
    {
        return $this->hasMany(EscolhaCandidato::class, 'etapas_candidatos_id', 'id');
    }
}