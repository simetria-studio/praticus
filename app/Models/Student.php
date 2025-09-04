<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'cpf',
        'rg',
        'birth_date',
        'gender',
        'photo',
        'enrollment',
        'school_id',
        'grade',
        'class',
        'class_id',
        'school_year',
        'status',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'postal_code',
        'country',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_cpf',
        'guardian_relationship',
        'medical_info',
        'observations',
        'enrollment_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'enrollment_date' => 'date',
        'school_year' => 'integer',
    ];

    // Relacionamentos
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByGrade($query, $grade)
    {
        return $query->where('grade', $grade);
    }

    public function scopeBySchoolYear($query, $year)
    {
        return $query->where('school_year', $year);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return "{$this->street}, {$this->number}" .
               ($this->complement ? ", {$this->complement}" : '') .
               " - {$this->neighborhood}, {$this->city} - {$this->state}, {$this->postal_code}";
    }

    public function getFormattedCpfAttribute()
    {
        if (!$this->cpf) return null;

        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->cpf);
    }

    public function getFormattedGuardianCpfAttribute()
    {
        if (!$this->guardian_cpf) return null;

        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->guardian_cpf);
    }

    public function getFormattedPostalCodeAttribute()
    {
        if (!$this->postal_code) return null;

        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $this->postal_code);
    }

    public function getAgeAttribute()
    {
        if (!$this->birth_date) return null;

        return Carbon::parse($this->birth_date)->age;
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'ativo' => 'Ativo',
            'inativo' => 'Inativo',
            'transferido' => 'Transferido',
            'formado' => 'Formado',
            'evadido' => 'Evadido',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'ativo' => 'bg-success',
            'inativo' => 'bg-secondary',
            'transferido' => 'bg-warning',
            'formado' => 'bg-info',
            'evadido' => 'bg-danger',
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }

    public function getGenderLabelAttribute()
    {
        $genders = [
            'masculino' => 'Masculino',
            'feminino' => 'Feminino',
            'outro' => 'Outro',
        ];

        return $genders[$this->gender] ?? $this->gender;
    }

    public function getGuardianRelationshipLabelAttribute()
    {
        $relationships = [
            'pai' => 'Pai',
            'mae' => 'Mãe',
            'avo' => 'Avô',
            'ava' => 'Avó',
            'tio' => 'Tio',
            'tia' => 'Tia',
            'responsavel_legal' => 'Responsável Legal',
            'outro' => 'Outro',
        ];

        return $relationships[$this->guardian_relationship] ?? $this->guardian_relationship;
    }

    public function getFullNameWithEnrollmentAttribute()
    {
        return "{$this->name} ({$this->enrollment})";
    }

    public function getGradeWithClassAttribute()
    {
        $classLabel = $this->schoolClass ? $this->schoolClass->name : ($this->class ? "Turma {$this->class}" : '');
        return trim($this->grade . ($classLabel ? " - {$classLabel}" : ''));
    }

    // Mutators
    public function setCpfAttribute($value)
    {
        $this->attributes['cpf'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }

    public function setGuardianCpfAttribute($value)
    {
        $this->attributes['guardian_cpf'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }

    public function setPostalCodeAttribute($value)
    {
        $this->attributes['postal_code'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }

    public function setGuardianPhoneAttribute($value)
    {
        $this->attributes['guardian_phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setEnrollmentAttribute($value)
    {
        $this->attributes['enrollment'] = strtoupper($value);
    }

    // Métodos auxiliares
    public function isActive()
    {
        return $this->status === 'ativo';
    }

    public function isMinor()
    {
        return $this->age < 18;
    }

    public function getSchoolName()
    {
        return $this->school ? $this->school->name : 'Escola não informada';
    }

    public function getPhotoUrl()
    {
        return $this->photo ? asset('storage/' . $this->photo) : asset('img/default-avatar.png');
    }

    public function getInitials()
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            if (strlen($word) > 0) {
                $initials .= strtoupper($word[0]);
            }
        }

        return substr($initials, 0, 2);
    }

    // Métodos para cálculos de notas
    public function getSubjectAverage($subject, $period = null, $schoolYear = null)
    {
        return Note::calculateAverage($this->id, $subject, $period, $schoolYear);
    }

    public function getPeriodAverage($period, $schoolYear = null)
    {
        $subjects = array_keys(Note::getSubjects());
        $averages = [];

        foreach ($subjects as $subject) {
            $average = $this->getSubjectAverage($subject, $period, $schoolYear);
            if ($average !== null) {
                $averages[] = $average;
            }
        }

        return !empty($averages) ? array_sum($averages) / count($averages) : null;
    }

    public function getGeneralAverage($schoolYear = null)
    {
        // Calcular média geral baseada nos semestres
        $semester1Average = $this->getSemesterAverage('1_semestre', $schoolYear);
        $semester2Average = $this->getSemesterAverage('2_semestre', $schoolYear);

        $averages = array_filter([$semester1Average, $semester2Average], function($avg) {
            return $avg !== null;
        });

        return !empty($averages) ? array_sum($averages) / count($averages) : null;
    }

    /**
     * Calcular média de um semestre específico
     */
    public function getSemesterAverage($semester, $schoolYear = null)
    {
        $subjects = array_keys(Note::getSubjects());
        $averages = [];

        foreach ($subjects as $subject) {
            $average = Note::calculateSemesterAverage($this->id, $subject, $semester, $schoolYear);
            if ($average !== null) {
                $averages[] = $average;
            }
        }

        return !empty($averages) ? array_sum($averages) / count($averages) : null;
    }

    /**
     * Calcular média mensal de um mês específico
     */
    public function getMonthlyAverage($month, $schoolYear = null)
    {
        $subjects = array_keys(Note::getSubjects());
        $averages = [];

        foreach ($subjects as $subject) {
            $average = $this->getSubjectAverage($subject, $month, $schoolYear);
            if ($average !== null) {
                $averages[] = $average;
            }
        }

        return !empty($averages) ? array_sum($averages) / count($averages) : null;
    }

    public function getSubjectStatus($subject, $schoolYear = null)
    {
        // Calcular média geral da disciplina considerando ambos os semestres
        $semester1Average = Note::calculateSemesterAverage($this->id, $subject, '1_semestre', $schoolYear);
        $semester2Average = Note::calculateSemesterAverage($this->id, $subject, '2_semestre', $schoolYear);

        $averages = array_filter([$semester1Average, $semester2Average], function($avg) {
            return $avg !== null;
        });

        if (empty($averages)) {
            return 'sem_notas';
        }

        $generalAverage = array_sum($averages) / count($averages);
        return $generalAverage >= 6.0 ? 'aprovado' : 'reprovado';
    }

    public function getAcademicStatus($schoolYear = null)
    {
        $subjects = array_keys(Note::getSubjects());
        $failed = 0;
        $subjectsWithNotes = 0;

        foreach ($subjects as $subject) {
            $status = $this->getSubjectStatus($subject, $schoolYear);
            if ($status === 'reprovado') {
                $failed++;
            }
            if ($status !== 'sem_notas') {
                $subjectsWithNotes++;
            }
        }

        // Se não há notas em nenhuma disciplina
        if ($subjectsWithNotes === 0) {
            return 'sem_notas';
        }

        if ($failed === 0) return 'aprovado';
        if ($failed <= 2) return 'recuperacao';
        return 'reprovado';
    }

    public function hasNotesInYear($schoolYear = null)
    {
        $year = $schoolYear ?? date('Y');
        return $this->notes()->where('school_year', $year)->exists();
    }

    public function getNotesCountByPeriod($period, $schoolYear = null)
    {
        $query = $this->notes()->where('period', $period);

        if ($schoolYear) {
            $query->where('school_year', $schoolYear);
        }

        return $query->count();
    }
}
