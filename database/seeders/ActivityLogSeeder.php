<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        if (!$user) {
            return;
        }

        $activities = [
            [
                'type' => 'create',
                'model' => 'Student',
                'model_id' => 1,
                'description' => 'Aluno "Maria Silva Santos" foi cadastrado',
                'user_id' => $user->id,
                'user_name' => $user->name,
                'created_at' => now()->subHours(2),
            ],
            [
                'type' => 'create',
                'model' => 'Certificate',
                'model_id' => 1,
                'description' => 'Certificado "CERT-2024-00001" gerado para Maria Silva Santos',
                'user_id' => $user->id,
                'user_name' => $user->name,
                'created_at' => now()->subHours(1),
            ],
            [
                'type' => 'download',
                'model' => 'Certificate',
                'model_id' => 1,
                'description' => 'PDF do certificado "CERT-2024-00001" foi baixado',
                'user_id' => $user->id,
                'user_name' => $user->name,
                'created_at' => now()->subMinutes(30),
            ],
            [
                'type' => 'create',
                'model' => 'Student',
                'model_id' => 2,
                'description' => 'Aluno "João Pedro Santos" foi cadastrado',
                'user_id' => $user->id,
                'user_name' => $user->name,
                'created_at' => now()->subMinutes(15),
            ],
            [
                'type' => 'view',
                'model' => 'Student',
                'model_id' => 1,
                'description' => 'Histórico escolar de Maria Silva Santos foi visualizado',
                'user_id' => $user->id,
                'user_name' => $user->name,
                'created_at' => now()->subMinutes(5),
            ],
        ];

        foreach ($activities as $activity) {
            ActivityLog::create($activity);
        }
    }
}
