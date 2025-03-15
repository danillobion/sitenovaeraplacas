<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\TipoPermissao;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        
        $user = Auth::user();
        $usuario = Usuario::find($user->id);

        if ($usuario && $usuario->hasPermission($permission)) {
            return $next($request);
        }else{
            return abort(403, 'Acesso negado');
        }

    }
}
