<?php

use App\Models\Papel;
use Illuminate\Database\Seeder;

class PapeisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lista[] = ['nome' => 'Suporte', 'descricao' => 'Tipo de usuário usado pelos desenvolvedores do sistema', 'email' => 'atendimento@mastertag.com.br', 'ativo' => 's'];
        $lista[] = ['nome' => 'Administrador', 'descricao' => 'Tipo de usuário administrador do sistema', 'email' => 'diretoria@bpse.com.br', 'ativo' => 's'];

        foreach ($lista as $papel) {
            Papel::create($papel);
        }
    }
}
