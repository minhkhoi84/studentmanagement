<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

abstract class BaseController extends Controller
{
    /**
     * Redirect with success message.
     */
    protected function redirectWithSuccess(string $route, string $message): RedirectResponse
    {
        return redirect()->route($route)->with('success', $message);
    }

    /**
     * Redirect with error message.
     */
    protected function redirectWithError(string $route, string $message): RedirectResponse
    {
        return redirect()->route($route)->with('error', $message);
    }

    /**
     * Redirect back with success message.
     */
    protected function redirectBackWithSuccess(string $message): RedirectResponse
    {
        return redirect()->back()->with('success', $message);
    }

    /**
     * Redirect back with error message.
     */
    protected function redirectBackWithError(string $message): RedirectResponse
    {
        return redirect()->back()->with('error', $message);
    }
}










