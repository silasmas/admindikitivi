<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Pour la route de finalisation vidéo : toujours renvoyer du JSON avec les détails
     * de l'exception afin de localiser l'erreur 500 (fichier, ligne, message).
     */
    public function render($request, Throwable $e)
    {
        if ($request->routeIs('video.chunk.finalize') || str_contains($request->path(), 'finalize-video-upload')) {
            $stackTrace = config('app.debug') ? $e->getTraceAsString() : null;
            if ($stackTrace && strlen($stackTrace) > 1500) {
                $stackTrace = substr($stackTrace, 0, 1500) . "\n...";
            }

            return response()->json([
                'error'            => 'Erreur lors de la finalisation.',
                'details'          => $e->getMessage(),
                'exception_class'  => get_class($e),
                'file'             => $e->getFile(),
                'line'             => $e->getLine(),
                'stack_trace'      => $stackTrace,
                'retry_finalize'   => true,
            ], 500);
        }

        return parent::render($request, $e);
    }
}
