<?php

namespace App\Http\Middleware;

use Closure;

class isAdminDirectorStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && ( $request->user()->type == 'Administrator' || $request->user()->type == 'Director' || $request->user()->type == 'Estudante')) {
            return $next($request);
        }
        return Response::json([
            'unauthorized' => 'Acesso não autorizado'
        ], 401);
    }
}
