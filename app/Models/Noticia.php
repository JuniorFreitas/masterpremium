<?php
declare(strict_types=1);

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Noticia
 *
 * @property int $id
 * @property string $titulo
 * @property string $slug
 * @property string|null $descricao
 * @property string $conteudo
 * @property int $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia whereConteudo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Arquivo[] $Anexo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia findSimilarSlugs($attribute, $config, $slug)
 * @property int $categoria_id
 * @property-read \App\Models\CategoriasNoticia $Categoria
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Noticia whereCategoriaId($value)
 */
class Noticia extends Model
{
    use Sluggable;
    protected $fillable = [
        'categoria_id',
        'titulo',
        'slug',
        'descricao',
        'conteudo',
        'ativo',
    ];
    protected $casts = [
        'id' => 'int',
        'categoria_id' => 'int',
        'titulo' => 'string',
        'slug' => 'string',
        'descricao' => 'string',
        'conteudo' => 'string',
        'ativo' => 'boolean',
    ];

    public function Categoria()
    {
        return $this->hasOne(CategoriasNoticia::class, 'id', 'categoria_id');
    }

    public function Anexo()
    {
        return $this->belongsToMany(Arquivo::class, 'pivot_noticias', 'noticia_id', 'arquivo_id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'titulo'
            ]
        ];
    }
}
