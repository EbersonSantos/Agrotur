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
        $this->call(UsersTableSeeder::class);
        $this->call(AnunciosTableSeeder::class);
        $this->call(HospedagensTableSeeder::class);
        $this->call(ServicosTableSeeder::class);
        $this->call(EnderecosTableSeeder::class);
        $this->call(TransacaosTableSeeder::class);
        // $this->call(Imagem_HospedagemsTableSeeder::class);
    }
}
