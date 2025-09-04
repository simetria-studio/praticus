<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject',
        'period',
        'grade',
        'max_grade',
        'evaluation_type',
        'evaluation_date',
        'school_year',
        'class',
        'weight',
        'observations',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'grade' => 'decimal:2',
        'max_grade' => 'decimal:2',
        'weight' => 'decimal:2',
        'school_year' => 'integer',
    ];

    // Relacionamentos
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Scopes
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    public function scopeBySchoolYear($query, $year)
    {
        return $query->where('school_year', $year);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'ativa');
    }

    public function scopeByEvaluationType($query, $type)
    {
        return $query->where('evaluation_type', $type);
    }

    // Accessors
    public function getFormattedGradeAttribute()
    {
        return number_format($this->grade, 2, ',', '.');
    }

    public function getFormattedMaxGradeAttribute()
    {
        return number_format($this->max_grade, 2, ',', '.');
    }

    public function getPercentageAttribute()
    {
        return ($this->grade / $this->max_grade) * 100;
    }

    public function getFormattedPercentageAttribute()
    {
        return number_format($this->percentage, 1, ',', '.') . '%';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'ativa' => 'Ativa',
            'cancelada' => 'Cancelada',
            'corrigida' => 'Corrigida',
        ];

        return $labels[$this->status] ?? 'Desconhecido';
    }

    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'ativa' => 'bg-success',
            'cancelada' => 'bg-danger',
            'corrigida' => 'bg-warning',
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }

    public function getEvaluationTypeLabelAttribute()
    {
        $labels = [
            'prova' => 'Prova',
            'trabalho' => 'Trabalho',
            'participacao' => 'Participação',
            'seminario' => 'Seminário',
            'projeto' => 'Projeto',
            'exercicio' => 'Exercício',
            'apresentacao' => 'Apresentação',
            'recuperacao' => 'Recuperação',
            'media_semestral' => 'Média Semestral',
        ];

        return $labels[$this->evaluation_type] ?? ucfirst($this->evaluation_type);
    }

    public function getFormattedEvaluationDateAttribute()
    {
        return $this->evaluation_date ? $this->evaluation_date->format('d/m/Y') : null;
    }

    public function getConceptAttribute()
    {
        $percentage = $this->percentage;

        if ($percentage >= 90) return 'Excelente';
        if ($percentage >= 80) return 'Ótimo';
        if ($percentage >= 70) return 'Bom';
        if ($percentage >= 60) return 'Regular';
        return 'Insuficiente';
    }

    public function getConceptColorAttribute()
    {
        $percentage = $this->percentage;

        if ($percentage >= 90) return 'text-success';
        if ($percentage >= 80) return 'text-info';
        if ($percentage >= 70) return 'text-primary';
        if ($percentage >= 60) return 'text-warning';
        return 'text-danger';
    }

    // Métodos auxiliares
    public function isActive()
    {
        return $this->status === 'ativa';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelada';
    }

    public function isCorrected()
    {
        return $this->status === 'corrigida';
    }

    public function isPassing()
    {
        return $this->percentage >= 60; // Considera 60% como nota mínima
    }

    // Métodos estáticos para cálculos
    public static function calculateAverage($studentId, $subject, $period = null, $schoolYear = null)
    {
        $query = static::byStudent($studentId)
            ->bySubject($subject)
            ->active();

        if ($period) {
            $query->byPeriod($period);
        }

        if ($schoolYear) {
            $query->bySchoolYear($schoolYear);
        }

        $notes = $query->get();

        if ($notes->isEmpty()) {
            return null;
        }

        // Cálculo de média ponderada
        $totalWeight = $notes->sum('weight');
        $weightedSum = $notes->sum(function ($note) {
            return $note->grade * $note->weight;
        });

        return $totalWeight > 0 ? $weightedSum / $totalWeight : 0;
    }

    public static function getSubjects()
    {
        return [
            'matematica' => 'Matemática',
            'portugues' => 'Português',
            'historia' => 'História',
            'geografia' => 'Geografia',
            'ciencias' => 'Ciências',
            'fisica' => 'Física',
            'quimica' => 'Química',
            'biologia' => 'Biologia',
            'ingles' => 'Inglês',
            'espanhol' => 'Espanhol',
            'educacao_fisica' => 'Educação Física',
            'artes' => 'Artes',
            'filosofia' => 'Filosofia',
            'sociologia' => 'Sociologia',
            'literatura' => 'Literatura',
        ];
    }

    public static function getPeriods()
    {
        return [
            // Avaliações do 1º Semestre
            '1_ava' => '1ª AVA',
            '2_ava' => '2ª AVA',
            '3_ava' => '3ª AVA',
            '4_ava' => '4ª AVA',

            // Avaliações do 2º Semestre
            '5_ava' => '5ª AVA',
            '6_ava' => '6ª AVA',
            '7_ava' => '7ª AVA',
            '8_ava' => '8ª AVA',

            // Recuperações
            'recuperacao_1_semestre' => 'Recuperação 1º Semestre',
            'recuperacao_2_semestre' => 'Recuperação 2º Semestre',

            // Prova final
            'prova_final' => 'Prova Final',
        ];
    }

    public static function getEvaluationTypes()
    {
        return [
            'prova' => 'Prova',
            'trabalho' => 'Trabalho',
            'participacao' => 'Participação',
            'seminario' => 'Seminário',
            'projeto' => 'Projeto',
            'exercicio' => 'Exercício',
            'apresentacao' => 'Apresentação',
            'recuperacao' => 'Recuperação',
            'media_semestral' => 'Média Semestral',
        ];
    }

    /**
     * Obter avaliações de um semestre
     */
    public static function getEvaluationsBySemester($semester)
    {
        $evaluations = [
            '1_semestre' => ['1_ava', '2_ava', '3_ava', '4_ava'],
            '2_semestre' => ['5_ava', '6_ava', '7_ava', '8_ava'],
        ];

        return $evaluations[$semester] ?? [];
    }

    /**
     * Verificar se um período é de avaliação
     */
    public static function isEvaluationPeriod($period)
    {
        $evaluationPeriods = [
            '1_ava', '2_ava', '3_ava', '4_ava',
            '5_ava', '6_ava', '7_ava', '8_ava'
        ];

        return in_array($period, $evaluationPeriods);
    }

    /**
     * Verificar se um período é semestral
     */
    public static function isSemesterPeriod($period)
    {
        return in_array($period, ['1_semestre', '2_semestre']);
    }

    /**
     * Verificar se um período é de recuperação
     */
    public static function isRecoveryPeriod($period)
    {
        return in_array($period, ['recuperacao_1_semestre', 'recuperacao_2_semestre']);
    }

    /**
     * Verificar se um período é prova final
     */
    public static function isFinalExamPeriod($period)
    {
        return $period === 'prova_final';
    }

    /**
     * Calcular média semestral baseada nas avaliações
     */
    public static function calculateSemesterAverage($studentId, $subject, $semester, $schoolYear = null)
    {
        $evaluations = self::getEvaluationsBySemester($semester);

        if (empty($evaluations)) {
            return null;
        }

        $query = static::byStudent($studentId)
            ->bySubject($subject)
            ->active();

        if ($schoolYear) {
            $query->bySchoolYear($schoolYear);
        }

        $notes = $query->whereIn('period', $evaluations)->get();

        if ($notes->isEmpty()) {
            return null;
        }

        // Cálculo de média ponderada
        $totalWeight = $notes->sum('weight');
        $weightedSum = $notes->sum(function ($note) {
            return $note->grade * $note->weight;
        });

        return $totalWeight > 0 ? $weightedSum / $totalWeight : 0;
    }

    /**
     * Calcular média com recuperação (substitui a menor nota)
     */
    public static function calculateSemesterAverageWithRecovery($studentId, $subject, $semester, $schoolYear = null)
    {
        $evaluations = self::getEvaluationsBySemester($semester);

        if (empty($evaluations)) {
            return null;
        }

        $query = static::byStudent($studentId)
            ->bySubject($subject)
            ->active();

        if ($schoolYear) {
            $query->bySchoolYear($schoolYear);
        }

        // Buscar notas das avaliações
        $evaluationNotes = $query->whereIn('period', $evaluations)->get();

        // Buscar nota de recuperação
        $recoveryPeriod = $semester === '1_semestre' ? 'recuperacao_1_semestre' : 'recuperacao_2_semestre';
        $recoveryNote = $query->where('period', $recoveryPeriod)->first();

        if ($evaluationNotes->isEmpty()) {
            return null;
        }

        // Se não há recuperação, calcular média normal
        if (!$recoveryNote) {
            $totalWeight = $evaluationNotes->sum('weight');
            $weightedSum = $evaluationNotes->sum(function ($note) {
                return $note->grade * $note->weight;
            });
            return $totalWeight > 0 ? $weightedSum / $totalWeight : 0;
        }

        // Com recuperação: substituir a menor nota
        $allNotes = $evaluationNotes->toArray();

        // Encontrar a menor nota (considerando peso)
        $minNote = null;
        $minIndex = -1;
        $minWeightedGrade = PHP_FLOAT_MAX;

        foreach ($allNotes as $index => $note) {
            $weightedGrade = $note['grade'] * $note['weight'];
            if ($weightedGrade < $minWeightedGrade) {
                $minWeightedGrade = $weightedGrade;
                $minNote = $note;
                $minIndex = $index;
            }
        }

        // Substituir a menor nota pela recuperação
        if ($minIndex >= 0) {
            $allNotes[$minIndex]['grade'] = $recoveryNote->grade;
            $allNotes[$minIndex]['weight'] = $recoveryNote->weight;
        }

        // Calcular nova média
        $totalWeight = array_sum(array_column($allNotes, 'weight'));
        $weightedSum = array_sum(array_map(function ($note) {
            return $note['grade'] * $note['weight'];
        }, $allNotes));

        return $totalWeight > 0 ? $weightedSum / $totalWeight : 0;
    }
}
