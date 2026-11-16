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
        // Intercept all raw Database Query Exceptions to prevent SQL leak / Information Disclosure
        $exceptions->render(function (QueryException $e, Request $request) {
            Log::error('Database Query Exception: ' . $e->getMessage(), [
                'sql' => $e->getSql(),
                'ip' => $request->ip(),
                'user' => $request->user()?->id,
            ]);

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'දත්ත සැකසීමේ දෝෂයක් හඳුනාගන්නා ලදී. ඔබගේ දත්ත ආරක්ෂිතයි.',
                ], 500);
            }

            return response()->view('errors.custom', [
                'message' => 'පද්ධතිය සමඟ සම්බන්ධ වීමේදී දෝෂයක් මතු විය. ආරක්ෂක හේතූන් මත තාක්ෂණික විස්තර සඟවා ඇත.',
            ], 500);
        });

        // Intercept PDO Exceptions (connection drops, timeout, etc.)
        $exceptions->render(function (\PDOException $e, Request $request) {
            Log::error('Database Connection Exception: ' . $e->getMessage());

            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'පද්ධති සේවාදායකය (System Server) සමඟ සම්බන්ධ විය නොහැක. කරුණාකර සුළු මොහොතකින් නැවත උත්සාහ කරන්න.',
                ], 500);
            }

            return response()->view('errors.custom', [
                'message' => 'පද්ධති සේවාදායකය සමඟ සම්බන්ධ වීමේ දෝෂයකි.',
            ], 500);
        });

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
