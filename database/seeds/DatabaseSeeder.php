<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(HabilidadesTableSeeder::class);
//        $this->call(PapeisTableSeeder::class);
//        $this->call(PapeisHabilidadesTableSeeder::class);
//        $this->call(UsersTableSeeder::class);
//        $this->call(BancosTableSeeder::class);
//        $this->call(FeriadosTableSeeder::class);
//        $this->call(HorarioAcessoTableSeeder::class);
//        $this->call(ManutencaoProgramadaTableSeeder::class);
//        $this->call(EscolaridadesTableSeeder::class);
//        $this->call(CatVagasTableSeeder::class);
//          $this->call(ServicosTableSeeder::class);
    }
}
