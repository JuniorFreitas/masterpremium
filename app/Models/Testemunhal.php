<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testemunhal extends Model
{
    protected $fillable = [
        'nome',
        'subtitulo',
        'texto',
        'ativo',
    ];
    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'subtitulo' => 'string',
        'texto' => 'string',
        'ativo' => 'boolean',
    ];


    public function Anexo()
    {
        return $this->belongsToMany(Arquivo::class, 'pivot_testemunhals', 'testemunhal_id', 'arquivo_id');
    }
}
