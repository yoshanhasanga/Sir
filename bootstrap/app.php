<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Intercept all raw MySQL Query Exceptions to prevent SQL leak / Information Disclosure
        $exceptions->render(function (QueryException $e, Request $request) {
            Log::error('MySQL Query Exception intercepted: ' . $e->getMessage(), [
                'sql' => $e->getSql(),
                'ip' => $request->ip(),
                'user' => $request->user()?->id,
            ]);

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'දත්ත සමුදායේ (Database) දෝෂයක් හඳුනාගන්නා ලදී. ඔබගේ දත්ත ආරක්ෂිතයි. (Database transaction protected; technical details logged securely).',
                ], 500);
            }

            return response()->view('errors.custom', [
                'message' => 'දත්ත සමුදාය සමඟ සම්බන්ධ වීමේදී දෝෂයක් මතු විය. ආරක්ෂක හේතූන් මත විස්තර සඟවා ඇත.',
            ], 500);
        });

        // Intercept PDO Exceptions (connection drops, timeout, etc.)
        $exceptions->render(function (\PDOException $e, Request $request) {
            Log::error('MySQL PDO Exception intercepted: ' . $e->getMessage());

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'MySQL සේවාදායකය (Server) සමඟ සම්බන්ධ විය නොහැක. කරුණාකර නැවත උත්සාහ කරන්න.',
                ], 500);
            }

            return response()->view('errors.custom', [
                'message' => 'MySQL සේවාදායකය සමඟ සම්බන්ධ වීමේ දෝෂයකි.',
            ], 500);
        });

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
