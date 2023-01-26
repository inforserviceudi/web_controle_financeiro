<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CidadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nm_cidades = [];

        for( $i = 0; $i < count($nm_cidades); $i++ ){
            DB::table('estados')->insert([
                'nm_cidade'  => $nm_cidades[$i],
                'estado_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
