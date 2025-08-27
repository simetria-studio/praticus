<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'code',
        'grade',
        'period',
        'year',
        'capacity',
        'status',
        'description',
        'school_id',
        'coordinator_id',
        'subjects',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'year' => 'integer',
        'subjects' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relacionamentos
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function coordinator()
    {
        return $this->belongsTo(Teacher::class, 'coordinator_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'ativa');
    }

    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    // Helpers
    public function getTitleAttribute()
    {
        return $this->name . ' - ' . $this->year;
    }

    public function getPeriodLabelAttribute()
    {
        return [
            'manha' => 'Manhã',
            'tarde' => 'Tarde',
            'noite' => 'Noite',
        ][$this->period] ?? $this->period;
    }
}
