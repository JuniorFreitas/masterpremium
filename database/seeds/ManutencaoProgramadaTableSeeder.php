<?php

use App\Models\ManutencaoProgramada;
use Illuminate\Database\Seeder;

class ManutencaoProgramadaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ManutencaoProgramada::create([
            'datahora' => '27/03/2017 03:00:00',
            'ativo' => 1
        ]);
    }
}
