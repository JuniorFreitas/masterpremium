<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Papel
 *
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property string $ativo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Papel whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Papel whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Papel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Papel whereNome($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Habilidade[] $habilidades
 * @property string $email
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Papel whereEmail($value)
 */
class Papel extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'papel';
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    public function getDescriptionForEvent(string $eventName): string
    {
        return $eventName;
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->descricao = "";
    }

    protected $table = 'papeis';
    protected $fillable = [
        'nome', 'email', 'descricao', 'ativo'
    ];
    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'descricao' => 'string',
        'ativo' => 'boolean',
    ];

    public $timestamps = false;

    public function habilidades()
    {
        return $this->belongsToMany(Habilidade::class, 'papeis_habilidades');
    }
}
