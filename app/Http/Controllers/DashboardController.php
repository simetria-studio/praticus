<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Certificate;
use App\Models\Student;
use App\Models\School;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal
     */
    public function index()
    {
        // Estatísticas gerais
        $stats = [
            'students' => Student::count(),
            'schools' => School::count(),
            'certificates' => Certificate::count(),
            'notes' => Note::count(),
        ];

        // Estatísticas de atividades
        $activityStats = ActivityLog::stats();

        // Atividades recentes (últimas 10)
        $recentActivities = ActivityLog::recent(10);

        // Atividades de hoje
        $todayActivities = ActivityLog::today();

        // Atividades por tipo (últimos 7 dias)
        $activityTypes = ActivityLog::selectRaw('type, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->get();

        return view('dashboard', compact(
            'stats',
            'activityStats',
            'recentActivities',
            'todayActivities',
            'activityTypes'
        ));
    }
}
