<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\RecoveryNoteCreated;
use App\Models\Note;

class RecalculateSemesterAverage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RecoveryNoteCreated $event): void
    {
        $note = $event->note;

        // Determinar o semestre baseado no período da recuperação
        $semester = null;
        if ($note->period === 'recuperacao_1_semestre') {
            $semester = '1_semestre';
        } elseif ($note->period === 'recuperacao_2_semestre') {
            $semester = '2_semestre';
        }

        if (!$semester) {
            return; // Não é uma recuperação válida
        }

        // Recalcular média semestral com recuperação
        $average = Note::calculateSemesterAverageWithRecovery(
            $note->student_id,
            $note->subject,
            $semester,
            $note->school_year
        );

        if ($average !== null) {
            // Buscar ou criar registro de média semestral
            $semesterNote = Note::where('student_id', $note->student_id)
                ->where('subject', $note->subject)
                ->where('period', $semester)
                ->where('school_year', $note->school_year)
                ->where('evaluation_type', 'media_semestral')
                ->first();

            $noteData = [
                'student_id' => $note->student_id,
                'subject' => $note->subject,
                'period' => $semester,
                'grade' => round($average, 2),
                'max_grade' => 10.00,
                'evaluation_type' => 'media_semestral',
                'evaluation_date' => now(),
                'school_year' => $note->school_year,
                'weight' => 1.00,
                'observations' => "Média semestral recalculada automaticamente após recuperação em " . now()->format('d/m/Y H:i'),
                'status' => 'ativa',
                'created_by' => 'Sistema',
            ];

            if ($semesterNote) {
                // Atualizar média existente
                $semesterNote->update($noteData);
            } else {
                // Criar nova média
                Note::create($noteData);
            }
        }
    }
}
