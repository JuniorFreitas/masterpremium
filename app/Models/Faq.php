<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['curso_id', 'pergunta', 'resposta', 'ativo'];
    protected $casts = [
        'curso_id' => 'id',
        'pergunta' => 'string',
        'resposta' => 'string',
        'ativo' => 'boolean'
    ];
}
