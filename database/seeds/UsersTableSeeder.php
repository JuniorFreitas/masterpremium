<?php

use App\Models\Papel;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $papel_suporte = Papel::find(1); // Suporte

        User::create([
            'nome' => 'Suporte',
            'logradouro' => '',
            'complemento' => '',
            'bairro' => '',
            'municipio' => '',
            'cep' => '65110-000',
            'login' => 'mastertag',
            'password' => bcrypt('123456'),
            'tipo' => User::$ADMINISTRADOR,
            'grupo_id' => $papel_suporte->id,
            'cadastrou' => 1,
            'ativo' => 1,
            'temp' => 0,
            'ultimo_acesso' => date('Y-m-d H:i:s')
        ]);

        $papel_admin = Papel::find(2); // Administrador

        User::create([
            'nome' => 'Danniele Sanches',
            'logradouro' => '',
            'complemento' => '',
            'bairro' => '',
            'municipio' => '',
            'cep' => '65110-000',
            'login' => 'danni@bpse.com.br',
            'password' => bcrypt('123456'),
            'tipo' => User::$ADMINISTRADOR,
            'grupo_id' => $papel_admin->id,
            'cadastrou' => 1,
            'ativo' => 1,
            'temp' => 0,
            'ultimo_acesso' => date('Y-m-d H:i:s')
        ]);
    }
}
