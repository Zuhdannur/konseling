<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
            DB::table('user')->insert([
                'username' => $faker->userName,
                'password' => $faker->password,
                'role' => 'admin',
                'sekolah_id' => $faker->numberBetween(1, 10)
            ]);
        }
//        factory(App\User::class, 50)->create([
//            'name' => str_random(10),
//            'username' => str_random(10),
//            'password' => \Illuminate\Support\Facades\Hash::make('your_password'),
//            'role' => 'master'
//        ]);
//
//        \App\User::create([
//            'name'  => str_random(10),
//            'username' => str_random(10),
//            'password'  => \Illuminate\Support\Facades\Hash::make('your_password'),
//            'role' => 'master'
//        ]);
    }
}
