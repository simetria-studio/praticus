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
            '1_bimestre' => '1º Bimestre',
            '2_bimestre' => '2º Bimestre',
            '3_bimestre' => '3º Bimestre',
            '4_bimestre' => '4º Bimestre',
            '1_trimestre' => '1º Trimestre',
            '2_trimestre' => '2º Trimestre',
            '3_trimestre' => '3º Trimestre',
            '1_semestre' => '1º Semestre',
            '2_semestre' => '2º Semestre',
            'anual' => 'Anual',
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
        ];
    }
}
