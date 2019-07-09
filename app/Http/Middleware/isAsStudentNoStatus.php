<?php

namespace App\Http\Middleware;

use Closure;

class isAsStudentNoStatus
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
        if ($request->user() && ( $request->user()->type == 'SA' || ( $request->user()->type == 'Estudante' && $request->user()->enee == 'approved') ) {
            return $next($request);
        }
        return Response::json([
            'unauthorized' => 'Acesso não autorizado'
        ], 401);
    }
}
