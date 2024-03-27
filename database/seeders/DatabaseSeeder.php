<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        $user = [
            [
                'username' => 'admin',
                'name' => 'AkunAdmin',
                'email' => 'Admin@gmail.com',
                'level' => 'admin',
                'password'=>Hash::make('admin123')
            ],
            [
                'username' => 'rw01',
                'name' => 'RW01PH',
                'email' => 'rw01@gmail.com',
                'level' => 'user',
                'password'=>Hash::make('rw01ph')
            ],
            [
                'username' => 'rw02',
                'name' => 'RW02PH',
                'email' => 'rw02@gmal.com',
                'level' => 'user',
                'password'=>Hash::make('rw02ph')
            ]
        ];
        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
