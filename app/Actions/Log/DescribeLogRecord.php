<?php

namespace App\Actions\Log;

use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Throwable;

/**
 * Turns a log call into what the team may see: message, place and a short
 * trace, without personal data (CLAUDE.md rule 9). Queries lose their
 * bindings, traces lose their arguments, and mail addresses and long
 * numbers in messages are masked.
 */
class DescribeLogRecord
{
    /**
     * @param  array<string, mixed>  $context
     * @return array{message: string, exception: string|null, location: string|null, trace: string|null, fingerprint: string}
     */
    public function handle(string $level, string $message, array $context): array
    {
        $exception = ($context['exception'] ?? null) instanceof Throwable ? $context['exception'] : null;

        if ($exception) {
            $message = $exception instanceof QueryException
                ? "Datenbank: {$exception->getSql()}"
                : $exception->getMessage();
        }

        $message = Str::limit($this->scrub($message) ?: '(ohne Text)', 1000);
        $location = $exception ? $this->relative($exception->getFile()).':'.$exception->getLine() : null;
        $normalized = preg_replace('/\d+/', '#', $message) ?? $message;

        return [
            'message' => $message,
            'exception' => $exception ? $exception::class : null,
            'location' => $location,
            'trace' => $exception ? $this->trace($exception) : null,
            'fingerprint' => sha1($level.'|'.($exception ? $exception::class.'|'.$location : $normalized)),
        ];
    }

    public function scrub(string $text): string
    {
        // Terminal colour codes (the SSR server's errors) are noise in the log.
        $text = preg_replace('/\e\[[0-9;]*[A-Za-z]/', '', $text) ?? $text;
        $text = preg_replace('/[^\s@<>"\']+@[^\s@<>"\']+\.[a-z]{2,}/i', '[E-Mail]', $text) ?? $text;

        return preg_replace('/\+?\d[\d \/.-]{7,}\d/', '[Nummer]', $text) ?? $text;
    }

    /**
     * The request path with personal-link tokens hidden: a guest's link
     * opens their household's data.
     */
    public function path(string $path): string
    {
        return Str::limit(preg_replace('/[A-Za-z0-9_-]{20,}/', '…', $this->scrub($path)) ?? $path, 180);
    }

    /**
     * File, line and function of the first frames, never their arguments.
     */
    private function trace(Throwable $exception): string
    {
        return collect($exception->getTrace())
            ->take(12)
            ->map(fn (array $frame): string => trim(
                (isset($frame['file']) ? $this->relative($frame['file']).':'.($frame['line'] ?? '?').' ' : '')
                .($frame['class'] ?? '').($frame['type'] ?? '').$frame['function'].'()'
            ))
            ->implode("\n");
    }

    private function relative(string $path): string
    {
        return Str::after($path, base_path().DIRECTORY_SEPARATOR);
    }
}
