<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\School;
use App\Models\Certificate;
use App\Models\Note;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class SystemController extends Controller
{
    /**
     * Dashboard do sistema
     */
    public function dashboard()
    {
        // Estatísticas gerais
        $statistics = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_schools' => DB::table('schools')->exists() ? DB::table('schools')->count() : 0,
            'total_students' => DB::table('students')->exists() ? DB::table('students')->count() : 0,
        ];

        // Informações do sistema
        $systemInfo = [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'database_version' => $this->getDatabaseVersion(),
        ];

        // Atividades recentes
        $recentActivities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        // Usuários ativos nos últimos 30 dias
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))
            ->orderBy('last_login_at', 'desc')
            ->limit(10)
            ->get();

        // Gráfico de atividades por dia (últimos 7 dias)
        $activityChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = ActivityLog::whereDate('created_at', $date)->count();
            $activityChart[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }

        // Espaço em disco
        $diskUsage = $this->getDiskUsage();

        ActivityLog::log('view', 'System', 'Acessou dashboard do sistema');

        return view('system.dashboard', compact(
            'statistics', 'systemInfo', 'recentActivities', 'activeUsers',
            'activityChart', 'diskUsage'
        ));
    }

    /**
     * Configurações do sistema
     */
    public function settings()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'max_file_size' => ini_get('upload_max_filesize'),
            'memory_limit' => ini_get('memory_limit'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        // Configurações de email
        $emailSettings = [
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        // Configurações de banco de dados
        $dbSettings = [
            'connection' => config('database.default'),
            'host' => config('database.connections.mysql.host'),
            'port' => config('database.connections.mysql.port'),
            'database' => config('database.connections.mysql.database'),
        ];

        ActivityLog::log('view', 'System', 'Visualizou configurações do sistema');

        return view('system.settings', compact('settings', 'emailSettings', 'dbSettings'));
    }

    /**
     * Logs do sistema
     */
    public function logs(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filtros
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(15);

        // Dados para filtros
        $users = User::orderBy('name')->get(['id', 'name']);

        ActivityLog::log('view', 'System', 'Visualizou logs do sistema');

        return view('system.logs', compact('activities', 'users'));
    }

    /**
     * Informações do sistema
     */
    public function info()
    {
        // Informações do PHP
        $phpInfo = [
            'version' => PHP_VERSION,
            'sapi' => php_sapi_name(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'upload_tmp_dir' => ini_get('upload_tmp_dir'),
        ];

        // Informações do Laravel
        $laravelInfo = [
            'version' => app()->version(),
            'environment' => config('app.env'),
            'debug' => config('app.debug'),
            'url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
        ];

        // Informações do servidor
        $serverInfo = [
            'os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'http_host' => $_SERVER['HTTP_HOST'] ?? 'N/A',
            'server_port' => $_SERVER['SERVER_PORT'] ?? 'N/A',
            'server_protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'N/A',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'N/A',
            'uptime' => null, // Pode ser implementado posteriormente
        ];

        // Informações do banco de dados
        $databaseInfo = [
            'driver' => config('database.default'),
            'version' => $this->getDatabaseVersion(),
            'host' => config('database.connections.' . config('database.default') . '.host'),
            'port' => config('database.connections.' . config('database.default') . '.port'),
            'database' => config('database.connections.' . config('database.default') . '.database'),
            'username' => config('database.connections.' . config('database.default') . '.username'),
            'size' => $this->formatBytes($this->getDatabaseSize()),
        ];

        // Extensões PHP carregadas
        $phpExtensions = get_loaded_extensions();
        sort($phpExtensions);

        // Uso do disco
        $diskUsage = $this->getDiskUsage();

        ActivityLog::log('view', 'System', 'Visualizou informações do sistema');

        return view('system.info', compact(
            'phpInfo', 'laravelInfo', 'serverInfo', 'databaseInfo',
            'phpExtensions', 'diskUsage'
        ));
    }

    /**
     * Backup do sistema
     */
    public function backup()
    {
        try {
            // Criar backup do banco de dados
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            // Criar diretório se não existir
            if (!File::exists(dirname($path))) {
                File::makeDirectory(dirname($path), 0755, true);
            }

            // Executar backup
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.port'),
                config('database.connections.mysql.database'),
                $path
            );

            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                ActivityLog::log('backup', 'System', "Backup criado: {$filename}");

                return response()->json([
                    'success' => true,
                    'message' => 'Backup criado com sucesso!',
                    'filename' => $filename,
                    'size' => $this->formatBytes(filesize($path))
                ]);
            } else {
                throw new \Exception('Erro ao executar comando de backup');
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar backups
     */
    public function listBackups()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];

        if (File::exists($backupPath)) {
            $files = File::files($backupPath);

            foreach ($files as $file) {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'created_at' => Carbon::createFromTimestamp($file->getMTime()),
                    'path' => $file->getPathname()
                ];
            }

            // Ordenar por data de criação (mais recente primeiro)
            usort($backups, function($a, $b) {
                return $b['created_at']->timestamp - $a['created_at']->timestamp;
            });
        }

        return view('system.backups', compact('backups'));
    }

    /**
     * Download de backup
     */
    public function downloadBackup($filename)
    {
        $path = storage_path('app/backups/' . $filename);

        if (!File::exists($path)) {
            abort(404, 'Backup não encontrado');
        }

        ActivityLog::log('download', 'System', "Download de backup: {$filename}");

        return response()->download($path);
    }

    /**
     * Deletar backup
     */
    public function deleteBackup($filename)
    {
        $path = storage_path('app/backups/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
            ActivityLog::log('delete', 'System', "Backup deletado: {$filename}");

            return response()->json([
                'success' => true,
                'message' => 'Backup deletado com sucesso!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Backup não encontrado'
        ], 404);
    }

    /**
     * Limpar cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            ActivityLog::log('maintenance', 'System', 'Limpou cache do sistema');

            return response()->json([
                'success' => true,
                'message' => 'Cache limpo com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manutenção do sistema
     */
    public function maintenance(Request $request)
    {
        $isMaintenanceMode = File::exists(storage_path('framework/down'));

        if ($request->isMethod('post')) {
            if ($request->action === 'enable') {
                Artisan::call('down', ['--message' => 'Sistema em manutenção']);
                ActivityLog::log('maintenance', 'System', 'Ativou modo de manutenção');
                $message = 'Modo de manutenção ativado';
            } else {
                Artisan::call('up');
                ActivityLog::log('maintenance', 'System', 'Desativou modo de manutenção');
                $message = 'Modo de manutenção desativado';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'maintenance_mode' => $request->action === 'enable'
            ]);
        }

        return view('system.maintenance', compact('isMaintenanceMode'));
    }

    // Métodos auxiliares privados
    private function getDiskUsage()
    {
        $totalSpace = disk_total_space(storage_path());
        $freeSpace = disk_free_space(storage_path());
        $usedSpace = $totalSpace - $freeSpace;

        return [
            'total' => $this->formatBytes($totalSpace),
            'used' => $this->formatBytes($usedSpace),
            'free' => $this->formatBytes($freeSpace),
            'total_bytes' => $totalSpace,
            'free_bytes' => $freeSpace,
            'database_size' => $this->getDatabaseSize(),
            'usage_percentage' => round(($usedSpace / $totalSpace) * 100, 2)
        ];
    }

    private function getDatabaseVersion()
    {
        try {
            $result = DB::select('SELECT VERSION() as version');
            return $result[0]->version ?? 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getTablesCount()
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = DB::select("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = ?", [$database]);
            return $result[0]->count ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getDatabaseSize()
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$database]);

            return ($result[0]->size ?? 0) . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }
}
