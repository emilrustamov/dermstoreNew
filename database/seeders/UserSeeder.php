<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Администратор
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('[O8qW~gPa3u0xyu'), // Пароль: password
            'is_admin' => true, // Если у вас есть поле для админа
        ]);

        // Обычный пользователь
        User::create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => Hash::make('/?CDk[w7V#)7_Cq'), // Пароль: password
            'is_admin' => false, // Если у вас есть поле для админа
        ]);
    }
}
