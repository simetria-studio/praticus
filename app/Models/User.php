<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'department',
        'role',
        'status',
        'permissions',
        'last_login_at',
        'last_login_ip',
        'is_first_login',
        'password_changed_at',
        'coordinator_id',
        'is_coordinator',
        'last_school_access',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'is_first_login' => 'boolean',
            'permissions' => 'array',
            'is_coordinator' => 'boolean',
            'last_school_access' => 'datetime',
        ];
    }

    // Relacionamentos
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class);
    }

    public function school()
    {
        return $this->coordinator?->school;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeManagers($query)
    {
        return $query->where('role', 'manager');
    }

    public function scopeOperators($query)
    {
        return $query->where('role', 'operator');
    }

    // Métodos de permissão
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isOperator()
    {
        return $this->role === 'operator';
    }

    public function isCoordinator()
    {
        return $this->is_coordinator && $this->coordinator_id;
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasPermission($permission)
    {
        if ($this->isAdmin() || $this->isManager()) {
            return true; // Admin tem todas as permissões
        }

        if ($this->isCoordinator()) {
            $permissions = $this->getCoordinatorPermissions();
            return $permissions[$permission] ?? false;
        }

        if (!$this->permissions) {
            return false;
        }

        return in_array($permission, $this->permissions);
    }

    public function givePermission($permission)
    {
        $permissions = $this->permissions ?? [];

        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->permissions = $permissions;
            $this->save();
        }
    }

    public function revokePermission($permission)
    {
        if (!$this->permissions) {
            return;
        }

        $permissions = array_filter($this->permissions, function($p) use ($permission) {
            return $p !== $permission;
        });

        $this->permissions = array_values($permissions);
        $this->save();
    }

    public function canAccessSchool($schoolId)
    {
        if ($this->isAdmin() || $this->isManager()) {
            return true;
        }

        if ($this->isCoordinator()) {
            return $this->coordinator?->school_id == $schoolId;
        }

        return false;
    }

    public function canAccessCoordinator($coordinatorId)
    {
        if ($this->isAdmin() || $this->isManager()) {
            return true;
        }

        if ($this->isCoordinator()) {
            return $this->coordinator_id == $coordinatorId;
        }

        return false;
    }

    public function canAccessStudent($studentId)
    {
        if ($this->isAdmin() || $this->isManager()) {
            return true;
        }

        if ($this->isCoordinator()) {
            $student = \App\Models\Student::find($studentId);
            return $student && $student->school_id == $this->coordinator->school_id;
        }

        return false;
    }

    public function canAccessClass($classId)
    {
        if ($this->isAdmin() || $this->isManager()) {
            return true;
        }

        if ($this->isCoordinator()) {
            $class = \App\Models\SchoolClass::find($classId);
            return $class && $class->school_id == $this->coordinator->school_id;
        }

        return false;
    }

    public function canAccessTeacher($teacherId)
    {
        if ($this->isAdmin() || $this->isManager()) {
            return true;
        }

        if ($this->isCoordinator()) {
            $teacher = \App\Models\Teacher::find($teacherId);
            return $teacher && in_array($this->coordinator->school_id, $teacher->schools ?? []);
        }

        return false;
    }

    public function canAccessCertificate($certificateId)
    {
        if ($this->isAdmin() || $this->isManager()) {
            return true;
        }

        if ($this->isCoordinator()) {
            $certificate = \App\Models\Certificate::find($certificateId);
            return $certificate && $certificate->school_id == $this->coordinator->school_id;
        }

        return false;
    }

    public function canAccessNote($noteId)
    {
        if ($this->isAdmin() || $this->isManager()) {
            return true;
        }

        if ($this->isCoordinator()) {
            $note = \App\Models\Note::find($noteId);
            return $note && $note->school_id == $this->coordinator->school_id;
        }

        return false;
    }

    public function getCoordinatorPermissions()
    {
        if (!$this->isCoordinator()) {
            return [];
        }

        return $this->permissions ?? [
            'view_students' => true,
            'create_students' => true,
            'edit_students' => true,
            'delete_students' => false,
            'view_classes' => true,
            'create_classes' => true,
            'edit_classes' => true,
            'delete_classes' => false,
            'view_teachers' => true,
            'create_teachers' => false,
            'edit_teachers' => false,
            'delete_teachers' => false,
            'view_certificates' => true,
            'create_certificates' => true,
            'edit_certificates' => true,
            'delete_certificates' => false,
            'view_notes' => true,
            'create_notes' => true,
            'edit_notes' => true,
            'delete_notes' => false,
            'view_reports' => true,
            'export_data' => true,
        ];
    }

    public function updateLastSchoolAccess()
    {
        if ($this->isCoordinator()) {
            $this->update(['last_school_access' => now()]);
        }
    }

    public function getSchoolData()
    {
        if (!$this->isCoordinator()) {
            return null;
        }

        return [
            'school' => $this->coordinator->school,
            'students_count' => $this->coordinator->school->students()->count(),
            'classes_count' => $this->coordinator->school->classes()->count(),
            'teachers_count' => $this->coordinator->school->teachers()->count(),
            'certificates_count' => $this->coordinator->school->certificates()->count(),
        ];
    }

    // Accessors
    public function getRoleNameAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrador',
            'manager' => 'Gerente',
            'operator' => 'Operador',
            default => 'Desconhecido'
        };
    }

    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'active' => 'Ativo',
            'inactive' => 'Inativo',
            'suspended' => 'Suspenso',
            default => 'Desconhecido'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'suspended' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Gerar avatar com iniciais
        $initials = collect(explode(' ', $this->name))
            ->map(fn($name) => strtoupper(substr($name, 0, 1)))
            ->take(2)
            ->join('');

        return "https://ui-avatars.com/api/?name={$initials}&background=004AAD&color=ffffff&size=128&font-size=0.6";
    }

    // Métodos utilitários
    public function updateLastLogin($ip = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
            'is_first_login' => false,
        ]);
    }

    public function changePassword($newPassword)
    {
        $this->update([
            'password' => Hash::make($newPassword),
            'password_changed_at' => now(),
        ]);
    }

    public function activate()
    {
        $this->update(['status' => 'active']);
    }

    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }

    public function suspend()
    {
        $this->update(['status' => 'suspended']);
    }

    // Constantes para permissões
    const PERMISSIONS = [
        'users.view' => 'Ver usuários',
        'users.create' => 'Criar usuários',
        'users.edit' => 'Editar usuários',
        'users.delete' => 'Excluir usuários',
        'schools.view' => 'Ver escolas',
        'schools.create' => 'Criar escolas',
        'schools.edit' => 'Editar escolas',
        'schools.delete' => 'Excluir escolas',
        'students.view' => 'Ver estudantes',
        'students.create' => 'Criar estudantes',
        'students.edit' => 'Editar estudantes',
        'students.delete' => 'Excluir estudantes',
        'certificates.view' => 'Ver certificados',
        'certificates.create' => 'Criar certificados',
        'certificates.edit' => 'Editar certificados',
        'certificates.delete' => 'Excluir certificados',
        'notes.view' => 'Ver notas',
        'notes.create' => 'Criar notas',
        'notes.edit' => 'Editar notas',
        'notes.delete' => 'Excluir notas',
        'system.view' => 'Ver configurações do sistema',
        'system.edit' => 'Editar configurações do sistema',
        'reports.view' => 'Ver relatórios',
        'reports.export' => 'Exportar relatórios',
        'logs.view' => 'Ver logs do sistema',
    ];
}
