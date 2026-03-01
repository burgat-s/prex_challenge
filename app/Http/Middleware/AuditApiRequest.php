<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiLog;

class AuditApiRequest
{

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $requestBody = $request->except(['password', 'password_confirmation']);

        ApiLog::create([
            'user_id'       => $request->user('api')?->id, 
            'service'       => $request->path(),
            'request_body'  => empty($requestBody) ? null : $requestBody,
            'response_code' => $response->getStatusCode(),
            'response_body' => json_decode($response->getContent(), true),
            'ip_address'    => $request->ip(),
        ]);
    }
}