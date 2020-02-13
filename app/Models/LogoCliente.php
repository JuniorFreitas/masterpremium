<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LogoCliente extends Model
{
    protected $table = 'cliente_logo_sites';

    public function Fotos()
    {
        return $this->belongsToMany(Arquivo::class, 'cliente_logo_foto', 'cliente_id', 'arquivo_id')->withPivot(['ordem'])->orderBy('ordem');
    }
}
