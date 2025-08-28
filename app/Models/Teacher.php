<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Teacher extends Model
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
        'registration',
        'specialty',
        'degree',
        'institution',
        'graduation_year',
        'status',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'postal_code',
        'country',
        'hiring_date',
        'contract_type',
        'salary',
        'workload',
        'observations',
        'subjects',
        'schools',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hiring_date' => 'date',
        'graduation_year' => 'integer',
        'salary' => 'decimal:2',
        'subjects' => 'array',
        'schools' => 'array',
    ];

    // Relacionamentos
    public function notes()
    {
        return $this->hasMany(Note::class, 'created_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function coordinatedClasses()
    {
        return $this->hasMany(SchoolClass::class, 'coordinator_id');
    }

    public function schoolsData()
    {
        if (!$this->schools) {
            return collect();
        }
        
        return School::whereIn('id', $this->schools)->get();
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

    public function scopeBySpecialty($query, $specialty)
    {
        return $query->where('specialty', $specialty);
    }

    public function scopeBySchool($query, $schoolId)
    {
        return $query->whereJsonContains('schools', $schoolId);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->whereJsonContains('subjects', $subject);
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

    public function getFormattedPostalCodeAttribute()
    {
        if (!$this->postal_code) return null;

        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $this->postal_code);
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
            'aposentado' => 'Aposentado',
            'licenca' => 'Licença',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'ativo' => 'bg-success',
            'inativo' => 'bg-secondary',
            'aposentado' => 'bg-info',
            'licenca' => 'bg-warning',
            default => 'bg-secondary'
        };
    }

    public function getGenderLabelAttribute()
    {
        $labels = [
            'masculino' => 'Masculino',
            'feminino' => 'Feminino',
            'outro' => 'Outro',
        ];

        return $labels[$this->gender] ?? $this->gender;
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

    // Métodos utilitários
    public function activate()
    {
        $this->update(['status' => 'ativo']);
    }

    public function deactivate()
    {
        $this->update(['status' => 'inativo']);
    }

    public function addSubject($subject)
    {
        $subjects = $this->subjects ?? [];

        if (!in_array($subject, $subjects)) {
            $subjects[] = $subject;
            $this->update(['subjects' => $subjects]);
        }
    }

    public function removeSubject($subject)
    {
        $subjects = $this->subjects ?? [];

        $subjects = array_filter($subjects, function($s) use ($subject) {
            return $s !== $subject;
        });

        $this->update(['subjects' => array_values($subjects)]);
    }

    public function addSchool($schoolId)
    {
        $schools = $this->schools ?? [];

        if (!in_array($schoolId, $schools)) {
            $schools[] = $schoolId;
            $this->update(['schools' => $schools]);
        }
    }

    public function removeSchool($schoolId)
    {
        $schools = $this->schools ?? [];

        $schools = array_filter($schools, function($s) use ($schoolId) {
            return $s !== $schoolId;
        });

        $this->update(['schools' => array_values($schools)]);
    }

    // Constantes
    const STATUSES = [
        'ativo' => 'Ativo',
        'inativo' => 'Inativo',
        'aposentado' => 'Aposentado',
        'licenca' => 'Licença',
    ];

    const GENDERS = [
        'masculino' => 'Masculino',
        'feminino' => 'Feminino',
        'outro' => 'Outro',
    ];

    const CONTRACT_TYPES = [
        'efetivo' => 'Efetivo',
        'temporario' => 'Temporário',
        'contratado' => 'Contratado',
        'estagiario' => 'Estagiário',
    ];
}
