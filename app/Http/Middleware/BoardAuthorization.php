<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BoardAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $boardId = $request->route('id');
        /** @var User $user */
        $user = Auth::user();
        // dd($boardId);
        if ($user->ownedBoards()->where('id', $boardId)->exists() || $user->boards()->where('id', $boardId)->exists()) {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }
}
