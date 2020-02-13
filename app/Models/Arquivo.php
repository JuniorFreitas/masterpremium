<?php

namespace App\Models;

use Auth;
use finfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * App\Models\Arquivo
 *
 * @property int $id
 * @property int|null $quem_enviou
 * @property string $nome
 * @property bool $imagem
 * @property string|null $layout
 * @property string $extensao
 * @property string $file
 * @property string|null $thumb
 * @property bool $temporario
 * @property string|null $chave
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereChave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereExtensao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereQuemEnviou($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereTemporario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $url_delete
 * @property-read mixed $url_download
 * @property-read mixed $url_thumb
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo query()
 * @property int $bytes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Arquivo whereBytes($value)
 */
class Arquivo extends Model
{
    protected $table = 'arquivos';

    protected $fillable = [
        'quem_enviou',
        'nome',
        'imagem',
        'layout',
        'extensao',
        'file',
        'thumb',
        'bytes',
        'temporario',
        'chave',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'id' => 'int',
        'quem_enviou' => 'int', //user_id que enviou
        'nome' => 'string', // ou titulo
        //'url' => 'string',
        'imagem' => 'boolean',
        'layout' => 'string',
        'extensao' => 'string',
        'file' => 'string',
        'thumb' => 'string',
        'bytes' => 'int',
        'temporario' => 'boolean',
        'chave' => 'string',
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];
    public $timestamps = true;
    //protected $dateFormat = 'U';
    //const CREATED_AT = 'criado_em';
    //const UPDATED_AT = 'atualizado_em';


    protected $appends = ['url', 'urlThumb', 'urlDownload', 'urlDelete'];

    // MIME_TYPE ARQUIVOS
    const MIME_GIF = "image/gif";
    const MIME_JPG = "image/jpg";
    const MIME_JPEG = "image/jpeg";
    const MIME_PNG = "image/png";
    const MIME_PDF = "application/pdf";
    const MIME_DOCX = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
    const MIME_DOC = "application/msword";
    const MIME_XLS = "application/vnd.ms-excel";
    const MIME_XLSX = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
    const MIME_PPT = "application/vnd.ms-powerpoint";
    const MIME_PPTX = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
    const MIME_PPS = "application/vnd.ms-powerpoint";
    const MIME_PPSX = "application/vnd.openxmlformats-officedocument.presentationml.slideshow";
    const MIME_TXT = "text/plain";
    const MIME_RAR = "application/x-rar-compressed";
    const MIME_ZIP = "application/zip";

    public static $discos = [
        'disco-cloud',
        'disco-fotocurriculo',
        'disco-cliente',
        'public'
    ];


    public const DISCO_CLOUD = 'disco-cloud';
    public const DISCO_FOTOCURRICULO = 'disco-fotocurriculo';
    public const DISCO_CLIENTE = 'disco-cliente';
    public const DISCO_PROSPECT = 'disco-prospect';
    public const DISCO_PUBLICO = 'public';


    //Acessor ->data
    public function getUrlAttribute()
    {
        $url = self::buscaUrl($this->file);
        if ($url) {
            $url = str_replace("/" . $this->file, '', $url);
            switch (self::nomeDisco($this->file)) {

                case 'disco-fotocurriculo':
                case 'disco-cloud':
                case 'disco-cliente':
                    return $url . "/anexo/{$this->file}";
                    break;

                case 'public':
                    // no filesystems.php já vem com /g/imoveis
                    return $url . "/{$this->file}";
                    break;

            }
            return $url;
        }
        return "";

        //return $this->attributes['nome'] . $this->attributes['extensao'];
    }

    public function getUrlThumbAttribute()
    {
        $url = self::buscaUrl($this->thumb);
        if ($url) {
            $url = str_replace("/" . $this->thumb, '', $url);
            switch (self::nomeDisco($this->file)) {

                case 'disco-fotocurriculo':
                case 'disco-cloud':
                case 'disco-cliente':
                    // no filesystems.php já vem com /g/imoveis
                    return $url . "/anexo/{$this->thumb}";
                    break;
                case 'public':
                    // no filesystems.php já vem com /g/imoveis
                    return $url . "/{$this->thumb}";
                    break;

            }
            return $url;
        }
        return "";
    }

    public function getUrlDownloadAttribute()
    {
        $url = self::buscaUrl($this->file);
        if ($url) {
            $url = str_replace("/" . $this->file, '', $url);
            switch (self::nomeDisco($this->file)) {
                case 'disco-fotocurriculo':
                case 'disco-cloud':
                case 'disco-cliente':
                    // no filesystems.php já vem com /g/imoveis
                    return $url . "/anexo/{$this->file}";
                    break;
                case 'public':
                    // no filesystems.php já vem com /g/imoveis
                    return $url . "/{$this->file}";
                    break;

            }
        }
        return "";
    }

    public function getUrlDeleteAttribute()
    {
        $url = self::buscaUrl($this->file);
        if ($url) {
            $url = str_replace("/" . $this->file, '', $url);
            switch (self::nomeDisco($this->file)) {
                case 'disco-fotocurriculo':
                case 'disco-cloud':
                case 'disco-cliente':
                    // no filesystems.php já vem com /g/imoveis
                    return $url . "/anexo/{$this->file}";
                    break;
                case 'public':
                    // no filesystems.php já vem com /g/imoveis
                    return $url . "/{$this->file}";
                    break;
            }
        }
        return "";
    }

    public static function findByArquivo($arquivo)
    {
        $arquivo = self::whereFile($arquivo)->get()->first();
        if ($arquivo) {
            return $arquivo;
        }
        return false;
    }

    //retorn uma URL abosoluta
    public static function buscaUrl($arquivo)
    {
        $disco = self::disco($arquivo);
        if ($disco) {
            return $disco->url($arquivo);
        }
        return false;
    }

    //Retorna o Path
    public static function buscaPath($arquivo)
    {
        $disco = self::disco($arquivo);
        if ($disco) {
            return $disco->path($arquivo);
        }

        return false;
    }

    public static function buscaConteudo($arquivo)
    {
        $disco = self::disco($arquivo);
        if ($disco) {
            return $disco->get($arquivo);
        }

        return false;
    }

    public static function getMimeType($path)
    {
        $file = $path;
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file);
        $partes = explode('; ', $mime);
        return $partes[0];
    }

    //localiza em que disco esse arquivo esta
    private static function disco($arquivo)
    {
        foreach (self::$discos as $disco) {
            if (Storage::disk($disco)->exists($arquivo)) {
                return Storage::disk($disco);
            }
        }
        return false;
    }

    public static function nomeDisco($arquivo)
    {
        foreach (self::$discos as $disco) {
            if (Storage::disk($disco)->exists($arquivo)) {
                return $disco;
            }
        }
        return false;
    }

    public static function gerarNomeFoto($nome)
    {
        $partes = explode('.', $nome);
        return $partes[0] . '_g.' . $partes[1]; // nome_g.jpg por exemplo
    }

    public static function gerarNomeThumb($nome)
    {
        $partes = explode('.', $nome);
        return $partes[0] . '_p.' . $partes[1]; // nome_p.jpg por exemplo
    }

    // pegar o nome do arquivo sem extensao
    public static function pegarNomeArquivo($nome)
    {
        $partes = explode('.', $nome);
        return $partes[0];
    }

    public static function pegarDimensoes($path)
    {
        $tamanhos = getimagesize($path);
        return [
            'largura' => $tamanhos[0],
            'altura' => $tamanhos[1],
        ];
    }

    public static function seForImagem($path)
    {

        $mime = mime_content_type($path);

        $tipo = substr($mime, 0, 5);
        if ($tipo == "image") {
            return true;
        } else {
            return false;
        }

    }

    public static function pegarLayout($path)
    {
        if (self::seForImagem($path)) {
            $largura = Arquivo::pegarDimensoes($path)['largura'];
            $altura = Arquivo::pegarDimensoes($path)['altura'];
        }
        if ($largura == -1 || $altura == -1) {
            return "";
        }
        if ($largura > $altura) {
            return "paisagem";
        }
        if ($largura < $altura) {
            return "retrato";
        }
        if ($largura == $altura) {
            return "quadrado";
        }
    }

    public static function maiorComprimento($path)
    {
        if (self::seForImagem($path)) {
            $largura = self::pegarDimensoes($path)['largura'];
            $altura = self::pegarDimensoes($path)['altura'];
            return max($largura, $altura);
        }
        return false;
    }

    public static function calculaLaguraAlturaProporcional($path, $novaLargura)
    {
        $largura = self::pegarDimensoes($path)['largura'];
        $altura = self::pegarDimensoes($path)['altura'];

        if ($largura > $altura) {
            $nova_largura = $novaLargura;
            $nova_altura = ($altura * $novaLargura) / $largura;
        } else {

            $nova_largura = ($largura * $novaLargura) / $altura;
            $nova_altura = $novaLargura;
        }

        return [
            'largura' => floor($nova_largura),
            'altura' => floor($nova_altura),
        ];
    }

    public static function gravaArquivoCliente(Request $request, $nomePost, $nomeDisco): Arquivo
    {
        //Dados do arquivo
        $path = $request->file($nomePost)->path();
        $nome = Arquivo::pegarNomeArquivo($request->file($nomePost)->getClientOriginalName());
        $bytes = $request->file($nomePost)->getSize();
        $extensao = $request->file($nomePost)->extension(); // sem ponto
        $imagem = Arquivo::seForImagem($path);
        $largura = null;
        $altura = null;
        if ($imagem) {
            $largura = Arquivo::pegarDimensoes($path)['largura'];
            $altura = Arquivo::pegarDimensoes($path)['altura'];
        }

        $nomeDoArquivo = $request->file($nomePost)->store(null, $nomeDisco); // grava o arquivo direto do request
        //Se for Arquivo de imagem fazer dois arquivos
        if ($imagem) {
            $pathOriginal = Storage::disk($nomeDisco)->path($nomeDoArquivo);

            $thumb_path = Storage::disk($nomeDisco)->path(Arquivo::gerarNomeThumb($nomeDoArquivo));
            $fotoGrande_path = Storage::disk($nomeDisco)->path(Arquivo::gerarNomeFoto($nomeDoArquivo));

            //Thumb
            $novas = Arquivo::calculaLaguraAlturaProporcional($pathOriginal, 200);
            Image::make($pathOriginal)->resize($novas['largura'], $novas['altura'])->save($thumb_path);

            //Grande
            $tamanhoFinal = Arquivo::maiorComprimento($pathOriginal) > 300 ? 300 : Arquivo::maiorComprimento($pathOriginal);
            $novas = Arquivo::calculaLaguraAlturaProporcional($pathOriginal, $tamanhoFinal);
            Image::make($pathOriginal)->resize($novas['largura'], $novas['altura'])->save($fotoGrande_path);

            Storage::disk($nomeDisco)->delete($nomeDoArquivo);//apagar o original
        }

        //Salvando no banco
        if ($imagem) {
            $dados = [
                'quem_enviou' => Auth::id(), //user_id que enviou
                'nome' => $nome, // ou titulo
                'imagem' => true,
                'layout' => self::pegarLayout($fotoGrande_path),
                'extensao' => "." . $extensao,
                'file' => Arquivo::gerarNomeFoto($nomeDoArquivo),
                'thumb' => Arquivo::gerarNomeThumb($nomeDoArquivo),
                'bytes' => $bytes,
                'temporario' => true,
                'chave' => $request->get('chave'),
            ];

        } else {
            $dados = [
                'quem_enviou' => Auth::id(), //user_id que enviou
                'nome' => $nome, // ou titulo
                'imagem' => false,
                'layout' => null,
                'extensao' => "." . $extensao,
                'file' => $nomeDoArquivo,
                'thumb' => null,
                'bytes' => $bytes,
                'temporario' => true,
                'chave' => $request->get('chave'),
            ];
        }

        $model = self::create($dados);
        return $model;


    }

    public static function gravaArquivo(Request $request, $nomePost, $nomeDisco): Arquivo
    {

        //Dados do arquivo
        $path = $request->file($nomePost)->path();
        $nome = Arquivo::pegarNomeArquivo($request->file($nomePost)->getClientOriginalName());
        $bytes = $request->file($nomePost)->getSize();
        $extensao = $request->file($nomePost)->extension(); // sem ponto
        $imagem = Arquivo::seForImagem($path);
        $largura = null;
        $altura = null;
        if ($imagem) {
            $largura = Arquivo::pegarDimensoes($path)['largura'];
            $altura = Arquivo::pegarDimensoes($path)['altura'];
        }

        $nomeDoArquivo = $request->file($nomePost)->store(null, $nomeDisco); // grava o arquivo direto do request
        //Se for Arquivo de imagem fazer dois arquivos
        if ($imagem) {
            $pathOriginal = Storage::disk($nomeDisco)->path($nomeDoArquivo);

            $thumb_path = Storage::disk($nomeDisco)->path(Arquivo::gerarNomeThumb($nomeDoArquivo));
            $fotoGrande_path = Storage::disk($nomeDisco)->path(Arquivo::gerarNomeFoto($nomeDoArquivo));

            //Thumb
            $novas = Arquivo::calculaLaguraAlturaProporcional($pathOriginal, 200);
            Image::make($pathOriginal)->resize($novas['largura'], $novas['altura'])->save($thumb_path);

            //Grande
            $tamanhoFinal = Arquivo::maiorComprimento($pathOriginal) > 800 ? 800 : Arquivo::maiorComprimento($pathOriginal);
            $novas = Arquivo::calculaLaguraAlturaProporcional($pathOriginal, $tamanhoFinal);
            Image::make($pathOriginal)->resize($novas['largura'], $novas['altura'])->save($fotoGrande_path);

            Storage::disk($nomeDisco)->delete($nomeDoArquivo);//apagar o original
        }

        //Salvando no banco
        if ($imagem) {
            $dados = [
                'quem_enviou' => Auth::id(), //user_id que enviou
                'nome' => $nome, // ou titulo
                'imagem' => true,
                'layout' => self::pegarLayout($fotoGrande_path),
                'extensao' => "." . $extensao,
                'file' => Arquivo::gerarNomeFoto($nomeDoArquivo),
                'thumb' => Arquivo::gerarNomeThumb($nomeDoArquivo),
                'bytes' => $bytes,
                'temporario' => true,
                'chave' => $request->get('chave'),
            ];

        } else {
            $dados = [
                'quem_enviou' => Auth::id(), //user_id que enviou
                'nome' => $nome, // ou titulo
                'imagem' => false,
                'layout' => null,
                'extensao' => "." . $extensao,
                'file' => $nomeDoArquivo,
                'thumb' => null,
                'bytes' => $bytes,
                'temporario' => true,
                'chave' => $request->get('chave'),
            ];
        }

        $model = self::create($dados);
        return $model;


    }

    public static function gravaArquivoReal(Request $request, $nomePost, $nomeDisco): Arquivo
    {


        //Dados do arquivo
        $path = $request->file($nomePost)->path();
        $extensao = $request->file($nomePost)->extension(); // sem ponto
        $tmExt = strlen('.' . $extensao);

        $nome = substr($request->file($nomePost)->getClientOriginalName(), 0, -$tmExt);
        $bytes = $request->file($nomePost)->getSize();

        $imagem = Arquivo::seForImagem($path);
        $largura = null;
        $altura = null;
        if ($imagem) {
            $largura = Arquivo::pegarDimensoes($path)['largura'];
            $altura = Arquivo::pegarDimensoes($path)['altura'];
        }

        $nomeDoArquivo = $request->file($nomePost)->store(null, $nomeDisco); // grava o arquivo direto do request
        //Se for Arquivo de imagem fazer dois arquivos
        if ($imagem) {
            $pathOriginal = Storage::disk($nomeDisco)->path($nomeDoArquivo);

            $thumb_path = Storage::disk($nomeDisco)->path(Arquivo::gerarNomeThumb($nomeDoArquivo));
            $fotoGrande_path = Storage::disk($nomeDisco)->path(Arquivo::gerarNomeFoto($nomeDoArquivo));

            //Thumb
            $novas = Arquivo::calculaLaguraAlturaProporcional($pathOriginal, 75);
            Image::make($pathOriginal)->resize($novas['largura'], $novas['altura'])->save($thumb_path);

            //Grande
//            $tamanhoFinal = Arquivo::maiorComprimento($pathOriginal) > 800 ? 800 : Arquivo::maiorComprimento($pathOriginal);
//            $novas = Arquivo::calculaLaguraAlturaProporcional($pathOriginal, $tamanhoFinal);
            Image::make($pathOriginal)->save($fotoGrande_path);

            Storage::disk($nomeDisco)->delete($nomeDoArquivo);//apagar o original
        }

        //Salvando no banco
        if ($imagem) {
            $dados = [
                'quem_enviou' => Auth::id(), //user_id que enviou
                'nome' => $nome, // ou titulo
                'imagem' => true,
                'layout' => self::pegarLayout($fotoGrande_path),
                'extensao' => "." . $extensao,
                'file' => Arquivo::gerarNomeFoto($nomeDoArquivo),
                'thumb' => Arquivo::gerarNomeThumb($nomeDoArquivo),
                'bytes' => $bytes,
                'temporario' => true,
                'chave' => $request->get('chave'),
            ];

        } else {
            $dados = [
                'quem_enviou' => Auth::id(), //user_id que enviou
                'nome' => $nome, // ou titulo
                'imagem' => false,
                'layout' => null,
                'extensao' => "." . $extensao,
                'file' => $nomeDoArquivo,
                'thumb' => null,
                'bytes' => $bytes,
                'temporario' => true,
                'chave' => $request->get('chave'),
            ];
        }

        $model = self::create($dados);
        return $model;


    }

    // Apagar do banco e do disco qualquer arquivo passando somente o nome unico (campo file da tabela arquivos)
    public static function apagar($nome)
    {
        $disco = self::disco($nome);

        if ($disco && $disco->exists($nome)) {
            $model = self::findByArquivo($nome);
            if ($model) {
                if ($model->imagem) {
                    $disco->delete($model->file);
                    $disco->delete($model->thumb);
                } else {
                    $disco->delete($model->file);
                }
                $model->delete();
                return true;
            } else {
                return false;
            }

        }
        return false;

    }

    public function excluir()
    {
        $disco = self::disco($this->file);

        if ($disco && $disco->exists($this->file)) {

            if ($this->imagem) {
                $disco->delete($this->file);
                $disco->delete($this->thumb);
            } else {
                $disco->delete($this->file);
            }

        }
        $this->delete(); //apagar este model, e automaticamente apagar em cascata
        return true;
    }

    //Modificador ->data
    /*public function setUrlAttribute($value)
    {
        $data = new DataHora($value);
        $this->attributes['data'] = $data->dataInsert();
    }*/
}
