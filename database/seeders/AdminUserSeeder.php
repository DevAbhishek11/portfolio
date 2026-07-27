<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'dev.abhishek.ap11@gmail.com'],
            [
                'name'       => 'Admin',
                'password'   => Hash::make('Admin@123456'),
                'is_admin'   => true,
                'title'      => 'Full Stack Developer',
                'bio'        => 'Passionate full stack developer.',
            ]
        );

        $this->command->info('Admin user created: dev.abhishek.ap11@gmail.com / Admin@123456');
        $this->command->warn('Change this password immediately after first login!');
    }
}
