<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Habilidade
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Habilidade whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Habilidade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Habilidade whereNome($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Papel[] $papeis
 */
class Habilidade extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logName = 'habilidade';
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

    protected $table = 'habilidades';
    protected $fillable = [
        'nome', 'descricao'
    ];

    public $timestamps = false;

    public function papeis()
    {
        return $this->belongsToMany(Papel::class, 'papeis_habilidades');
    }

}
