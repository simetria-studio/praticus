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
    public function run(): void
    {
        // Criar ou atualizar usuário administrador
        User::updateOrCreate(
            ['email' => 'admin@sistemaescolar.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'status' => 'active',
                'department' => 'TI',
                'phone' => '(11) 99999-9999',
                'is_first_login' => false,
                'password_changed_at' => now(),
                'permissions' => array_keys(User::PERMISSIONS), // Admin tem todas as permissões
            ]
        );

        // Criar usuário gerente de exemplo
        User::updateOrCreate(
            ['email' => 'gerente@sistemaescolar.com'],
            [
                'name' => 'Gerente Sistema',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'role' => 'manager',
                'status' => 'active',
                'department' => 'Administração',
                'phone' => '(11) 88888-8888',
                'is_first_login' => false,
                'password_changed_at' => now(),
                'permissions' => [
                    'schools.view', 'schools.create', 'schools.edit',
                    'students.view', 'students.create', 'students.edit',
                    'certificates.view', 'certificates.create', 'certificates.edit',
                    'notes.view', 'notes.create', 'notes.edit',
                    'reports.view', 'reports.export'
                ],
            ]
        );

        // Criar usuário operador de exemplo
        User::updateOrCreate(
            ['email' => 'operador@sistemaescolar.com'],
            [
                'name' => 'Operador Sistema',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'role' => 'operator',
                'status' => 'active',
                'department' => 'Secretaria',
                'phone' => '(11) 77777-7777',
                'is_first_login' => false,
                'password_changed_at' => now(),
                'permissions' => [
                    'students.view', 'students.create', 'students.edit',
                    'certificates.view', 'certificates.create',
                    'notes.view', 'notes.create', 'notes.edit',
                ],
            ]
        );
    }
}
