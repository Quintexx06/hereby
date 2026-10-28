<?php

namespace App\Support;

use Inertia\ExceptionResponse;

/**
 * An error answering a click inside the app shows as the ErrorPage, in the
 * same words as views/errors, instead of Inertia's framed dialog, whose
 * buttons cannot leave the frame. Full page loads keep the Blade pages,
 * and a 500 keeps the debug page while debugging.
 */
class InertiaErrorPage
{
    private const array STATUSES = [403, 404, 419, 429, 500, 503];

    private const array RETRYABLE = [419, 429, 500, 503];

    public function __invoke(ExceptionResponse $response): ?ExceptionResponse
    {
        $status = $response->statusCode();

        if (! $response->request->header('X-Inertia') || ! in_array($status, self::STATUSES, true)) {
            return null;
        }

        if ($status === 500 && config('app.debug')) {
            return null;
        }

        // Guests keep their language; everywhere else the platform speaks Swiss German.
        if (! in_array(app()->getLocale(), ['de_CH', 'fr', 'it', 'en'], true) || (app()->getLocale() === 'en' && ! $response->request->is('i/*'))) {
            app()->setLocale('de_CH');
        }

        /** @var array{title: string, body: string} $copy */
        $copy = __("errors.{$status}");

        return $response->render('ErrorPage', [
            'status' => $status,
            'title' => $copy['title'],
            'body' => $copy['body'],
            'home' => __('errors.home'),
            'reload' => in_array($status, self::RETRYABLE, true) ? __('errors.reload') : null,
        ])->withSharedData();
    }
}
