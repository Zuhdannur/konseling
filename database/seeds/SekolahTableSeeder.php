<?php

use Illuminate\Database\Seeder;

class SekolahTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('id_ID');

        for($i = 1; $i <= 10; $i++){

            // insert data ke table pegawai menggunakan Faker
            DB::table('tbl_sekolah')->insert([
                'nama_sekolah' => $faker->company,
                'alamat' => $faker->address
            ]);
        }
    }
}
