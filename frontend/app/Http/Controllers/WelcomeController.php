<?php

/**
 * Controller presenting the publicly accessible welcome page for the Powered Learning frontend.
 *
 * Inputs: HTTP requests received on the root path via the web middleware stack.
 * Outputs: HTML response rendering the welcome Blade view for unauthenticated visitors.
 */

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

/**
 * Handles display of the landing page.
 */
class WelcomeController extends Controller
{
    /**
     * Render the welcome page for visitors.
     *
     * Inputs: none directly; relies on the current HTTP request context.
     * Outputs: View representing the welcome template.
     */
    public function __invoke(): View
    {
        return view('welcome');
    }
}
