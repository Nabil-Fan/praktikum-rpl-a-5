<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {

        // Semua error pada API route selalu dikembalikan sebagai JSON
        // agar aplikasi mobile bisa membaca pesan error dengan konsisten

        // Error validasi (422) — field tidak sesuai aturan
        $exceptions->render(function (ValidationException $validationException, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Data yang dikirim tidak valid.',
                    'errors'  => $validationException->errors(),
                ], 422);
            }
        });

        // Error 404 — endpoint atau resource tidak ditemukan
        $exceptions->render(function (NotFoundHttpException $notFoundException, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Endpoint atau data tidak ditemukan.',
                ], 404);
            }
        });

        // Error 405 — method HTTP tidak diizinkan (misal POST ke endpoint GET)
        $exceptions->render(function (MethodNotAllowedHttpException $methodException, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Method tidak diizinkan untuk endpoint ini.',
                ], 405);
            }
        });

    })->create();
