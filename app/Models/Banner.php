<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Banner
 *
 * @property int $id
 * @property int $arquivo_id
 * @property string $titulo
 * @property string $url
 * @property int $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereArquivoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Banner whereUrl($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Arquivo[] $Anexo
 */
class Banner extends Model
{
    protected $fillable = [
        'titulo',
        'url',
        'ativo',
    ];
    protected $casts = [
        'id' => 'int',
        'titulo' => 'string',
        'url' => 'string',
        'ativo' => 'boolean',
    ];

    public function Anexo()
    {
        return $this->belongsToMany(Arquivo::class, 'pivot_banners', 'banner_id', 'arquivo_id');
    }

}
