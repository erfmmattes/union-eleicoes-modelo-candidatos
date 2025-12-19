<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EleitorLogado extends Model
{
    use HasFactory;

    protected $table = 'eleitores_logados';

    protected $fillable = [
        'eleitor_id',
        'ip',
    ];

    public function eleitor()
    {
        return $this->belongsTo(Eleitor::class, 'eleitor_id');
    }
}