<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\usuarios;
use Symfony\Component\HttpFoundation\Response;

class IsEntregasMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && in_array((int) Auth::user()->tipo, [1, 3], true)) {
            return $next($request);
        }

        $usuarioQrId = $request->session()->get('usuario_entregas');
        if ($usuarioQrId && usuarios::whereKey($usuarioQrId)
            ->where('tipo', 3)
            ->where('deshabilitado', 0)
            ->exists()) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
