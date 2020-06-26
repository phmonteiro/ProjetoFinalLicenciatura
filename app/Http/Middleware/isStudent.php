<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;
class isStudent
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
        if ($request->user() && $request->user()->type == 'Estudante' && $request->user()->enee == 'approved' && $request->user()->inactive == null) {
            return $next($request);
        }
        return Response::json([
            'unauthorized' => 'Acesso não autorizado'
        ], 401);
    }
}
