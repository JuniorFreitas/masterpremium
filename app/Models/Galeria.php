<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Galeria extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'galeria';
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

    protected $fillable = [
        'titulo',
        'descricao',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'id' => 'int',
        'titulo' => 'string',
        'descricao' => 'string',
        'ordem' => 'int',
        'ativo' => 'boolean',
    ];

    public function Fotos()
    {
        return $this->belongsToMany(Arquivo::class, 'foto_galerias', 'galeria_id', 'arquivo_id')->withPivot(['ordem'])->orderBy('ordem');
    }
}
