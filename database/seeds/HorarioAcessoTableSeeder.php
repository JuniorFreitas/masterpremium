<?php

use App\Models\HorarioAcesso;
use Illuminate\Database\Seeder;

class HorarioAcessoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HorarioAcesso::create([
            'abertura' => '07:00:00',
            'fechamento' => '20:00:00',
            'ativo' => '1'
        ]);
    }
}
