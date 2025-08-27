<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coordinator;
use App\Models\School;

class CoordinatorSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::all();

        if ($schools->isEmpty()) {
            $this->command->warn('Nenhuma escola encontrada. Execute SchoolSeeder primeiro.');
            return;
        }

        $coordinators = [
            [
                'name' => 'Maria Silva Santos',
                'email' => 'maria.silva@escola.com',
                'phone' => '11999887766',
                'cpf' => '12345678901',
                'registration' => 'COORD001',
                'specialty' => 'Pedagogia',
                'degree' => 'Licenciatura em Pedagogia',
                'institution' => 'Universidade de São Paulo',
                'graduation_year' => 2015,
                'status' => 'ativo',
                'coordinated_grades' => ['1º ano', '2º ano', '3º ano'],
                'coordinated_subjects' => ['Português', 'Matemática', 'História'],
                'hiring_date' => '2020-02-01',
                'contract_type' => 'efetivo',
                'salary' => 3500.00,
                'workload' => '40h semanais',
                'street' => 'Rua das Flores',
                'number' => '123',
                'neighborhood' => 'Centro',
                'city' => 'São Paulo',
                'state' => 'SP',
                'postal_code' => '01234-567',
                'observations' => 'Coordenadora pedagógica com experiência em educação infantil.',
            ],
            [
                'name' => 'João Carlos Oliveira',
                'email' => 'joao.oliveira@escola.com',
                'phone' => '11988776655',
                'cpf' => '98765432100',
                'registration' => 'COORD002',
                'specialty' => 'Administração Escolar',
                'degree' => 'Bacharelado em Administração',
                'institution' => 'Universidade Estadual de Campinas',
                'graduation_year' => 2012,
                'status' => 'ativo',
                'coordinated_grades' => ['4º ano', '5º ano'],
                'coordinated_subjects' => ['Ciências', 'Geografia'],
                'hiring_date' => '2018-03-15',
                'contract_type' => 'efetivo',
                'salary' => 3800.00,
                'workload' => '40h semanais',
                'street' => 'Avenida Paulista',
                'number' => '456',
                'neighborhood' => 'Bela Vista',
                'city' => 'São Paulo',
                'state' => 'SP',
                'postal_code' => '01310-100',
                'observations' => 'Coordenador administrativo com foco em gestão escolar.',
            ],
            [
                'name' => 'Ana Paula Costa',
                'email' => 'ana.costa@escola.com',
                'phone' => '11977665544',
                'cpf' => '45678912300',
                'registration' => 'COORD003',
                'specialty' => 'Psicopedagogia',
                'degree' => 'Especialização em Psicopedagogia',
                'institution' => 'Pontifícia Universidade Católica',
                'graduation_year' => 2018,
                'status' => 'ativo',
                'coordinated_grades' => ['6º ano', '7º ano', '8º ano'],
                'coordinated_subjects' => ['Psicopedagogia', 'Orientação Educacional'],
                'hiring_date' => '2021-08-01',
                'contract_type' => 'contratado',
                'salary' => 3200.00,
                'workload' => '30h semanais',
                'street' => 'Rua Augusta',
                'number' => '789',
                'neighborhood' => 'Consolação',
                'city' => 'São Paulo',
                'state' => 'SP',
                'postal_code' => '01212-000',
                'observations' => 'Psicopedagoga especializada em dificuldades de aprendizagem.',
            ],
        ];

        foreach ($coordinators as $index => $coordinatorData) {
            $school = $schools[$index % $schools->count()];

            Coordinator::create(array_merge($coordinatorData, [
                'school_id' => $school->id,
            ]));
        }

        $this->command->info('Coordenadores criados com sucesso!');
    }
}
