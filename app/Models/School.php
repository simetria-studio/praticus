<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'cnpj',
        'phone',
        'email',
        'website',
        'description',
        'type',
        'level',
        'active',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relacionamentos
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function coordinators()
    {
        return $this->hasMany(Coordinator::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        return "{$this->street}, {$this->number}" .
               ($this->complement ? ", {$this->complement}" : '') .
               " - {$this->neighborhood}, {$this->city} - {$this->state}, {$this->postal_code}";
    }

    public function getFormattedCnpjAttribute()
    {
        if (!$this->cnpj) return null;

        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $this->cnpj);
    }

    public function getFormattedPostalCodeAttribute()
    {
        if (!$this->postal_code) return null;

        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $this->postal_code);
    }

    public function getTypeLabel()
    {
        $types = [
            'publica' => 'Pública',
            'privada' => 'Privada',
            'federal' => 'Federal',
            'estadual' => 'Estadual',
            'municipal' => 'Municipal',
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getLevelLabel()
    {
        $levels = [
            'infantil' => 'Educação Infantil',
            'fundamental' => 'Ensino Fundamental',
            'medio' => 'Ensino Médio',
            'superior' => 'Ensino Superior',
            'tecnico' => 'Ensino Técnico',
            'todos' => 'Todos os Níveis',
        ];

        return $levels[$this->level] ?? $this->level;
    }

    // Mutators
    public function setCnpjAttribute($value)
    {
        $this->attributes['cnpj'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setPostalCodeAttribute($value)
    {
        $this->attributes['postal_code'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }
}
