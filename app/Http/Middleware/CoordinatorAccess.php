<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CoordinatorAccess
{
    public function handle(Request $request, Closure $next, string $resource = null): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin e Manager têm acesso total
        if ($user->isAdmin() || $user->isManager()) {
            return $next($request);
        }

        // Coordenador precisa verificar permissões
        if ($user->isCoordinator()) {
            // Verificar se tem permissão para acessar o recurso
            if ($resource && !$user->hasPermission("view_{$resource}")) {
                abort(403, 'Acesso negado. Você não tem permissão para visualizar este recurso.');
            }

            // Atualizar último acesso à escola
            $user->updateLastSchoolAccess();

            return $next($request);
        }

        // Usuários sem permissão adequada
        abort(403, 'Acesso negado. Permissões insuficientes.');
    }
}
