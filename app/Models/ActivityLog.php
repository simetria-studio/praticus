<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'model',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'user_id',
        'user_name',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registrar uma nova atividade
     */
    public static function log($type, $model, $description, $modelId = null, $oldValues = null, $newValues = null)
    {
        if (!Auth::check()) {
            return;
        }

        return self::create([
            'type' => $type,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }

    /**
     * Obter atividades recentes
     */
    public static function recent($limit = 10)
    {
        return self::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obter atividades por usuário
     */
    public static function byUser($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obter atividades por tipo
     */
    public static function byType($type, $limit = 10)
    {
        return self::where('type', $type)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obter atividades de hoje
     */
    public static function today()
    {
        return self::whereDate('created_at', today())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obter estatísticas das atividades
     */
    public static function stats()
    {
        return [
            'today' => self::whereDate('created_at', today())->count(),
            'week' => self::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => self::whereMonth('created_at', now()->month)->count(),
            'total' => self::count()
        ];
    }

    /**
     * Obter ícone baseado no tipo de atividade
     */
    public function getIconAttribute()
    {
        $icons = [
            'create' => 'fas fa-plus-circle text-success',
            'update' => 'fas fa-edit text-warning',
            'delete' => 'fas fa-trash text-danger',
            'view' => 'fas fa-eye text-info',
            'download' => 'fas fa-download text-primary',
            'login' => 'fas fa-sign-in-alt text-success',
            'logout' => 'fas fa-sign-out-alt text-muted'
        ];

        return $icons[$this->type] ?? 'fas fa-circle text-secondary';
    }

    /**
     * Obter cor baseada no tipo de atividade
     */
    public function getColorAttribute()
    {
        $colors = [
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'danger',
            'view' => 'info',
            'download' => 'primary',
            'login' => 'success',
            'logout' => 'secondary'
        ];

        return $colors[$this->type] ?? 'secondary';
    }

    /**
     * Formatar data para exibição
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Obter tempo relativo (há X minutos)
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
