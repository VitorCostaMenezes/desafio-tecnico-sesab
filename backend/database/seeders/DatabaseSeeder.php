<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Number;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // perfis

        DB::table('profiles')->insert([
            'profile_name' => 'ADM',
        ]);

        DB::table('profiles')->insert([
            'profile_name' => 'TÉCNICO',
        ]);

        DB::table('profiles')->insert([
            'profile_name' => 'USER',
        ]);


        //usuários

        DB::table('users')->insert([
            'name' => Str::random(5).' '.Str::random(8),
            'email' => Str::random(10).'@example.com',
            'cpf' => '685.655.548-21',
            'profile_id' => 1,
            'password' => Hash::make('password'),
            'created_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => Str::random(7).' '.Str::random(6),
            'email' => Str::random(10).'@example.com',
            'cpf' => '411.232.177-50',
            'profile_id' => 2,
            'password' => Hash::make('password'),
            'created_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => Str::random(4).' '.Str::random(10),
            'email' => Str::random(10).'@example.com',
            'cpf' => '869.944.539-36',
            'profile_id' => 3,
            'password' => Hash::make('password'),
            'created_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => Str::random(6).' '.Str::random(8),
            'email' => Str::random(10).'@example.com',
            'cpf' => '607.489.726-33',
            'profile_id' => 3,
            'password' => Hash::make('password'),
            'created_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => Str::random(8).' '.Str::random(3),
            'email' => Str::random(10).'@example.com',
            'cpf' => '673.118.880-85',
            'profile_id' => 1,
            'password' => Hash::make('password'),
            'created_at' => now(),
        ]);


        // Endereços

        DB::table('adresses')->insert([
            'logradouro' => Str::random(10).' '.Str::random(10),
            'cep' => '08556-330',
            'created_at' => now(),
        ]);

        DB::table('adresses')->insert([
            'logradouro' => "Rua Padre Norberto",
            'cep' => '13841-153',
            'created_at' => now(),
        ]);

        DB::table('adresses')->insert([
            'logradouro' => Str::random(6).' '.Str::random(8),
            'cep' => '31742-512',
            'created_at' => now(),
        ]);

        DB::table('adresses')->insert([
            'logradouro' => Str::random(8).' '.Str::random(6).' '.Str::random(10),
            'cep' => '13478-230',
            'created_at' => now(),
        ]);

        DB::table('adresses')->insert([
            'logradouro' => Str::random(3).' '.Str::random(7).' '.Str::random(6),
            'cep' => '30710-300',
            'created_at' => now(),
        ]);


        // Relações entre usuários e endereços

        DB::table('relations')->insert([
            'adress_id' => 1,
            'user_id'=> 1,
        ]);

        DB::table('relations')->insert([
            'adress_id' => 2,
            'user_id'=> 1,
        ]);

        DB::table('relations')->insert([
            'adress_id' => 3,
            'user_id'=> 2,
        ]);

        DB::table('relations')->insert([
            'adress_id' => 4,
            'user_id'=> 2,
        ]);

        DB::table('relations')->insert([
            'adress_id' => 5,
            'user_id'=> 2,
        ]);

        DB::table('relations')->insert([
            'adress_id' => 1,
            'user_id'=> 3,
        ]);
        DB::table('relations')->insert([
            'adress_id' => 4,
            'user_id'=> 4,
        ]);
        DB::table('relations')->insert([
            'adress_id' => 1,
            'user_id'=> 5,
        ]);
        DB::table('relations')->insert([
            'adress_id' => 2,
            'user_id'=> 5,
        ]);

        DB::table('relations')->insert([
            'adress_id' => 3,
            'user_id'=> 5,
        ]);

        DB::table('relations')->insert([
            'adress_id' => 4,
            'user_id'=> 5,
        ]);

        DB::table('relations')->insert([
            'adress_id' => 5,
            'user_id'=> 5,
        ]);


    }
}
