<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminView;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Switches an admin between the team view and their own couple view, and
 * lands on that view's start page.
 */
class SwitchViewController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $view = AdminView::from($request->validate([
            'view' => ['required', Rule::enum(AdminView::class)],
        ])['view']);

        $request->session()->put(AdminView::SESSION_KEY, $view->value);

        return $view === AdminView::Team
            ? to_route('admin.weddings.index')
            : to_route('dashboard');
    }
}
