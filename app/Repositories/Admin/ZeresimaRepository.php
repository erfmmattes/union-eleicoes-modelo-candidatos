<?php

namespace App\Repositories\Admin;

use App\Models\Voto;

class ZeresimaRepository
{
    public function contarVotos(): int
    {
        return Voto::count();
    }
}