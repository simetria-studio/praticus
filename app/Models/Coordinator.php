<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coordinator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'cpf',
        'registration',
        'specialty',
        'degree',
        'institution',
        'graduation_year',
        'status',
        'school_id',
        'user_id',
        'coordinated_grades',
        'coordinated_subjects',
        'hiring_date',
        'contract_type',
        'salary',
        'workload',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'postal_code',
        'country',
        'observations',
        'photo',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'coordinated_grades' => 'array',
        'coordinated_subjects' => 'array',
        'hiring_date' => 'date',
        'salary' => 'decimal:2',
    ];

    // Relacionamentos
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'coordinator_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function scopeBySpecialty($query, $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    public function scopeByGrade($query, $grade)
    {
        return $query->whereJsonContains('coordinated_grades', $grade);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->whereJsonContains('coordinated_subjects', $subject);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        if (!$this->street) return null;

        return "{$this->street}, {$this->number}" .
               ($this->complement ? ", {$this->complement}" : '') .
               " - {$this->neighborhood}, {$this->city} - {$this->state}, {$this->postal_code}";
    }

    public function getFormattedCpfAttribute()
    {
        if (!$this->cpf) return null;

        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->cpf);
    }

    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;

        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        if (strlen($phone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
        } elseif (strlen($phone) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
        }

        return $this->phone;
    }

    public function getFormattedSalaryAttribute()
    {
        if (!$this->salary) return null;

        return 'R$ ' . number_format($this->salary, 2, ',', '.');
    }

    public function getAgeAttribute()
    {
        if (!$this->birth_date) return null;

        return $this->birth_date->age;
    }

    public function getYearsOfServiceAttribute()
    {
        if (!$this->hiring_date) return null;

        return $this->hiring_date->diffInYears(now());
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'ativo' => 'Ativo',
            'inativo' => 'Inativo',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'ativo' => 'bg-success',
            'inativo' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }

        // Gerar avatar com iniciais
        $initials = collect(explode(' ', $this->name))
            ->map(fn($name) => strtoupper(substr($name, 0, 1)))
            ->take(2)
            ->join('');

        return "https://ui-avatars.com/api/?name={$initials}&background=058D4F&color=ffffff&size=128&font-size=0.6";
    }

    public function getCoordinatedGradesListAttribute()
    {
        if (!$this->coordinated_grades) return 'Não definido';

        return implode(', ', $this->coordinated_grades);
    }

    public function getCoordinatedSubjectsListAttribute()
    {
        if (!$this->coordinated_subjects) return 'Não definido';

        return implode(', ', $this->coordinated_subjects);
    }

    // Mutators
    public function setCpfAttribute($value)
    {
        $this->attributes['cpf'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setPostalCodeAttribute($value)
    {
        $this->attributes['postal_code'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setRegistrationAttribute($value)
    {
        $this->attributes['registration'] = strtoupper($value);
    }

    // Métodos utilitários
    public function activate()
    {
        $this->update(['status' => 'ativo']);
    }

    public function deactivate()
    {
        $this->update(['status' => 'inativo']);
    }

    public function addGrade($grade)
    {
        $grades = $this->coordinated_grades ?? [];

        if (!in_array($grade, $grades)) {
            $grades[] = $grade;
            $this->update(['coordinated_grades' => $grades]);
        }
    }

    public function removeGrade($grade)
    {
        $grades = $this->coordinated_grades ?? [];

        $grades = array_filter($grades, function($g) use ($grade) {
            return $g !== $grade;
        });

        $this->update(['coordinated_grades' => array_values($grades)]);
    }

    public function addSubject($subject)
    {
        $subjects = $this->coordinated_subjects ?? [];

        if (!in_array($subject, $subjects)) {
            $subjects[] = $subject;
            $this->update(['coordinated_subjects' => $subjects]);
        }
    }

    public function removeSubject($subject)
    {
        $subjects = $this->coordinated_subjects ?? [];

        $subjects = array_filter($subjects, function($s) use ($subject) {
            return $s !== $subject;
        });

        $this->update(['coordinated_subjects' => array_values($subjects)]);
    }

    // Constantes
    const STATUSES = [
        'ativo' => 'Ativo',
        'inativo' => 'Inativo',
    ];

    const CONTRACT_TYPES = [
        'efetivo' => 'Efetivo',
        'temporario' => 'Temporário',
        'contratado' => 'Contratado',
        'estagiario' => 'Estagiário',
    ];
}
