<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voto extends Model
{
    use HasFactory;

    protected $table = 'votos';

    protected $fillable = [
        'eleitor_id',
        'voto',
        'votado_em',
        'etapa',
    ];

    protected $casts = [
        'votado_em' => 'datetime',
    ];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class);
    }

    public function setVotoAttribute($value)
    {
        $this->attributes['voto'] = encrypt($value);
    }

    public function getVotoAttribute($value)
    {
        return decrypt($value);
    }

    public function votouEm(\App\Models\EscolhaCandidato $candidato): bool
    {
        $votoCripto = $this->getOriginal('voto');

        try {
            $votoReal = \Illuminate\Support\Facades\Crypt::decryptString($votoCripto);

            $votoData = json_decode($votoReal, true);

            if (!isset($votoData['escolhas']) || !is_array($votoData['escolhas'])) {
                return false;
            }

            return in_array((string)$candidato->id, $votoData['escolhas'], true);

        } catch (\Exception $e) {
            return false;
        }
    }
}