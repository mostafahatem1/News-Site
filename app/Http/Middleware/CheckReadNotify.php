<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckReadNotify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->query('notify')) {
            // Mark  notification as read
            $notifiction = auth()->user()->unreadNotifications()->Where('id', $request->query('notify'))->first();
            if ($notifiction) {
                $notifiction->markAsRead();
            }
            
        }
        return $next($request);
    }
}
