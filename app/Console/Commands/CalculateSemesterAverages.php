<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Note;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;

class CalculateSemesterAverages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notes:calculate-semester-averages {--year=} {--semester=} {--class=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcula automaticamente as médias semestrais baseadas nas avaliações';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->option('year') ?: date('Y');
        $semester = $this->option('semester');
        $classId = $this->option('class');
        $force = $this->option('force');

        $this->info("Calculando médias semestrais para o ano {$year}...");

        // Determinar semestres para processar
        $semesters = $semester ? [$semester] : ['1_semestre', '2_semestre'];

        foreach ($semesters as $currentSemester) {
            $this->info("Processando {$currentSemester}...");

            // Buscar turmas
            $query = SchoolClass::with('students');
            if ($classId) {
                $query->where('id', $classId);
            }
            $classes = $query->get();

            if ($classes->isEmpty()) {
                $this->warn("Nenhuma turma encontrada.");
                continue;
            }

            $totalProcessed = 0;
            $totalCreated = 0;

            foreach ($classes as $class) {
                $this->info("Processando turma: {$class->name}");

                foreach ($class->students as $student) {
                    $this->processStudentSemester($student, $currentSemester, $year, $force);
                    $totalProcessed++;
                }
            }

            $this->info("Processamento do {$currentSemester} concluído. {$totalProcessed} alunos processados.");
        }

        $this->info("Cálculo de médias semestrais concluído!");
    }

    private function processStudentSemester($student, $semester, $year, $force = false)
    {
        $subjects = array_keys(Note::getSubjects());

        foreach ($subjects as $subject) {
            // Verificar se já existe média semestral para esta disciplina
            $existingAverage = Note::where('student_id', $student->id)
                ->where('subject', $subject)
                ->where('period', $semester)
                ->where('school_year', $year)
                ->first();

            if ($existingAverage && !$force) {
                continue; // Pular se já existe e não forçado
            }

            // Calcular média semestral com recuperação
            $average = Note::calculateSemesterAverageWithRecovery($student->id, $subject, $semester, $year);

            if ($average !== null) {
                $noteData = [
                    'student_id' => $student->id,
                    'subject' => $subject,
                    'period' => $semester,
                    'grade' => round($average, 2),
                    'max_grade' => 10.00,
                    'evaluation_type' => 'media_semestral',
                    'evaluation_date' => now(),
                    'school_year' => $year,
                    'weight' => 1.00,
                    'observations' => "Média semestral calculada automaticamente em " . now()->format('d/m/Y H:i'),
                    'status' => 'ativa',
                    'created_by' => 'Sistema',
                ];

                if ($existingAverage) {
                    // Atualizar média existente
                    $existingAverage->update($noteData);
                    $this->line("  Atualizada média de {$subject} para {$student->name}: " . round($average, 2));
                } else {
                    // Criar nova média
                    Note::create($noteData);
                    $this->line("  Criada média de {$subject} para {$student->name}: " . round($average, 2));
                }
            }
        }
    }
}
