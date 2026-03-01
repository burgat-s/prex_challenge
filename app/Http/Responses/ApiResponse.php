<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Respuesta estándar para casos de éxito.
     */
    public static function success(mixed $data = null, string $message = 'Operación exitosa', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    /**
     * Respuesta estándar para errores.
     */
    public static function error(string $message = 'Ocurrió un error', int $code = 400, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors
        ], $code);
    }
}