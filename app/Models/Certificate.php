<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_id',
        'certificate_number',
        'certificate_type',
        'course_level',
        'course_name',
        'completion_year',
        'completion_date',
        'issue_date',
        'school_name',
        'school_cnpj',
        'school_authorization',
        'school_inep',
        'school_address',
        'student_name',
        'student_cpf',
        'student_birth_date',
        'student_birth_place',
        'student_nationality',
        'father_name',
        'mother_name',
        'director_name',
        'director_title',
        'secretary_name',
        'secretary_title',
        'issue_city',
        'issue_state',
        'status',
        'observations',
        'pdf_path',
        'verification_code',
        'hash',
    ];

    protected $casts = [
        'completion_date' => 'date',
        'issue_date' => 'date',
        'student_birth_date' => 'date',
        'completion_year' => 'integer',
    ];

    // Relacionamentos
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Scopes
    public function scopeEmitted($query)
    {
        return $query->where('status', 'emitido');
    }

    public function scopeByCertificateType($query, $type)
    {
        return $query->where('certificate_type', $type);
    }

    public function scopeByCourseLevel($query, $level)
    {
        return $query->where('course_level', $level);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('completion_year', $year);
    }

    // Accessors
    public function getFormattedStudentCpfAttribute()
    {
        if (!$this->student_cpf) return null;

        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->student_cpf);
    }

    public function getFormattedSchoolCnpjAttribute()
    {
        if (!$this->school_cnpj) return null;

        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $this->school_cnpj);
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'emitido' => 'Emitido',
            'cancelado' => 'Cancelado',
            'reemitido' => 'Reemitido',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'emitido' => 'bg-success',
            'cancelado' => 'bg-danger',
            'reemitido' => 'bg-warning',
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }

    public function getCertificateTypeLabelAttribute()
    {
        $types = [
            'conclusao' => 'Certificado de Conclusão',
            'historico' => 'Histórico Escolar',
            'declaracao' => 'Declaração',
        ];

        return $types[$this->certificate_type] ?? $this->certificate_type;
    }

    public function getCourseLevelLabelAttribute()
    {
        $levels = [
            'ensino_medio' => 'Ensino Médio',
            'ensino_fundamental' => 'Ensino Fundamental',
            'educacao_infantil' => 'Educação Infantil',
            'ensino_tecnico' => 'Ensino Técnico',
            'ensino_superior' => 'Ensino Superior',
        ];

        return $levels[$this->course_level] ?? $this->course_level;
    }

    public function getFormattedCompletionDateAttribute()
    {
        return $this->completion_date ? $this->completion_date->format('d/m/Y') : null;
    }

    public function getFormattedIssueDateAttribute()
    {
        return $this->issue_date ? $this->issue_date->format('d/m/Y') : null;
    }

    public function getFormattedStudentBirthDateAttribute()
    {
        return $this->student_birth_date ? $this->student_birth_date->format('d/m/Y') : null;
    }

    public function getStudentAgeAtCompletionAttribute()
    {
        if (!$this->student_birth_date || !$this->completion_date) return null;

        return Carbon::parse($this->student_birth_date)->diffInYears($this->completion_date);
    }

        public function getCompletionDateWrittenAttribute()
    {
        if (!$this->completion_date) return '';

        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        $month = $months[$this->completion_date->month];

        return $month;
    }

        public function getStudentBirthDateWrittenAttribute()
    {
        if (!$this->student_birth_date) return '';

        $months = [
            1 => 'JANEIRO', 2 => 'FEVEREIRO', 3 => 'MARÇO', 4 => 'ABRIL',
            5 => 'MAIO', 6 => 'JUNHO', 7 => 'JULHO', 8 => 'AGOSTO',
            9 => 'SETEMBRO', 10 => 'OUTUBRO', 11 => 'NOVEMBRO', 12 => 'DEZEMBRO'
        ];

        $month = $months[$this->student_birth_date->month];

        return $month;
    }

    public function getPdfUrlAttribute()
    {
        return $this->pdf_path ? asset('storage/' . $this->pdf_path) : null;
    }

    // Métodos auxiliares
    public function isEmitted()
    {
        return $this->status === 'emitido';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelado';
    }

    public function isReissued()
    {
        return $this->status === 'reemitido';
    }

    public function generateCertificateNumber()
    {
        $year = date('Y');
        $lastNumber = static::where('certificate_number', 'like', "{$year}%")->count();
        $sequencial = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);

        return "{$year}{$sequencial}";
    }

    public function generateVerificationCode()
    {
        return strtoupper(uniqid());
    }

    public function generateHash()
    {
        $data = $this->certificate_number . $this->student_cpf . $this->completion_date;
        return hash('sha256', $data);
    }

    public static function findByVerificationCode($code)
    {
        return static::where('verification_code', $code)->first();
    }

    // Mutators
    public function setStudentCpfAttribute($value)
    {
        $this->attributes['student_cpf'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }

    public function setSchoolCnpjAttribute($value)
    {
        $this->attributes['school_cnpj'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }

    public function setStudentNameAttribute($value)
    {
        $this->attributes['student_name'] = strtoupper($value);
    }

    public function setSchoolNameAttribute($value)
    {
        $this->attributes['school_name'] = strtoupper($value);
    }

    public function setFatherNameAttribute($value)
    {
        $this->attributes['father_name'] = $value ? strtoupper($value) : null;
    }

    public function setMotherNameAttribute($value)
    {
        $this->attributes['mother_name'] = strtoupper($value);
    }

    public function setDirectorNameAttribute($value)
    {
        $this->attributes['director_name'] = strtoupper($value);
    }

    public function setSecretaryNameAttribute($value)
    {
        $this->attributes['secretary_name'] = $value ? strtoupper($value) : null;
    }

    public function setStudentBirthPlaceAttribute($value)
    {
        $this->attributes['student_birth_place'] = strtoupper($value);
    }

    public function setIssueCityAttribute($value)
    {
        $this->attributes['issue_city'] = strtoupper($value);
    }

    public function setIssueStateAttribute($value)
    {
        $this->attributes['issue_state'] = strtoupper($value);
    }
}
