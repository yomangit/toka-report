<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleUserPermit
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        if (!$user || !$user->rolePermit) {
            return abort(403, 'Unauthorized');
        }

        // Ambil nama role dari tabel relasi (misal: 'admin', 'moderator')
        $userRoleName = strtolower($user->rolePermit->name_role_user);

        if (!in_array($userRoleName, $roles)) {
            return $this->redirectToForbiddenPage($userRoleName);
        }

        return $next($request);
    }

    protected function redirectToForbiddenPage(string $role)
    {
        return match ($role) {
            'administration' => redirect('/')->with('error', 'Tidak diizinkan'),
            'auth' => redirect('/')->with('error', 'Akses dibatasi'),
            default => abort(403, 'Unauthorized'),
        };
    }
}
