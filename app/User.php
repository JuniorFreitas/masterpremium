<?php

namespace App;

use App\Models\GrupoCloud;
use App\Models\Papel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use MasterTag\DataHora;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 * App\User
 *
 * @property int $id
 * @property string $nome
 * @property string|null $logradouro
 * @property string|null $complemento
 * @property string|null $bairro
 * @property int $municipio
 * @property string|null $cep
 * @property string|null $login
 * @property string|null $password
 * @property string $tipo
 * @property int $grupo_id
 * @property string $cadastrou
 * @property int $ativo
 * @property int|null $temp
 * @property string $ultimo_acesso
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $remember_token
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Papel[] $papeis
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBairro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCadastrou($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereComplemento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGrupoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLogradouro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMunicipio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUltimoAcesso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $uf
 * @property-read \App\Models\Papel $Papel
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUf($value)
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'logradouro', 'complemento', 'bairro', 'municipio', 'cep', 'login',
        'password', 'tipo', 'grupo_id', 'cadastrou', 'ativo', 'temp', 'ultimo_acesso'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'id' => 'int',
        'nome' => 'string',
        'logradouro' => 'string',
        'complemento' => 'string',
        'bairro' => 'string',
        'municipio' => 'string',
        'cep' => 'string',
        'login' => 'string',
        'password' => 'string',
        'tipo' => 'string',
        'grupo_id' => 'int',
        'cadastrou' => 'int',
        'ativo' => 'boolean',
        'temp' => 'boolean',
        'ultimo_acesso' => 'datetime:d/m/Y H:i:s',
    ];

    private $listaDeHabilidade = [];


    public static $ADMINISTRADOR = "administrador";
    public static $PESSOA_FISICA = "pessoa_fisica";
    public static $PESSOA_JURIDICA = "pessoa_juridica";
    public static $CORRETOR = "corretor";
    public static $BENEFICIARIO_PF = "beneficiario_pf";
    public static $BENEFICIARIO_PJ = "beneficiario_pj";
    public static $FUNCIONARIO = "funcionario";
    public static $PRESTADOR_PF = "prestador_pf";
    public static $PRESTADOR_PJ = "prestador_pj";

    public static $CONTA_CAIXA = 95;
    public static $CONTA_IMOBILIARIA = 96;
    public static $CONTA_BANCO = 97;
    public static $CONTA_TROCO = 493;

    // retorna um array de habilidades
    public function listaDeHabilidades()
    {
        if (count($this->listaDeHabilidade) == 0) {
            // buscar no banco qual é o papel dele. e dair fazer o array com todas as habilidades que ele tem
            $lista = collect([]);

            //foreach ($this->papel as $papel) {

            //$habilidades = $papel->habilidades->pluck('nome');
            $habilidades = $this->papel->habilidades->pluck('nome');
            foreach ($habilidades as $habilidade) {

                if ($lista->search($habilidade) === false) {

                    $lista->push($habilidade);

                }
            }

            //}
            $this->listaDeHabilidade = $lista->toArray();
        }
        return $this->listaDeHabilidade;
    }


    public function setUltimoAcessoAttribute($value)
    {
        $datahora = new DataHora($value);
        $this->attributes['ultimo_acesso'] = $datahora->dataHoraInsert();
    }

    public function Papel()
    {
        return $this->hasOne(Papel::class, 'id', 'grupo_id');

    }

    public function GrupoCloud()
    {
        return $this->hasOne(GrupoCloud::class, 'id', 'grupo_cloud_id');
    }

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritDoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
