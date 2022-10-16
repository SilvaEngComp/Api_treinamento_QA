<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Empresa Teste",
            "email" => "valquiriameloengenharia@gmail.com",
            "password" => "1234",
            "cpf" => "34500820000",
            "cnpj" => "98932207000107",
        ]);
    }
}
