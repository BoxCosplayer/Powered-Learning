<?php

/**
 * Controller presenting the profile view so authenticated users can review their details.
 *
 * Inputs: HTTP requests routed through the auth middleware containing an authenticated user context.
 * Outputs: HTML response rendering the profile Blade view populated with the current user's data.
 */

namespace App\Http\Controllers;

use App\Models\HistoryEntry;
use App\Models\PredictedGrade;
use App\Models\Subject;
use App\Models\Type;
use App\Models\User;
use Illuminate\Contracts\View\View;

/**
 * Handles display of the profile page for logged-in users.
 */
class ProfileController extends Controller
{
    /**
     * Render the profile view for the authenticated user.
     *
     * Inputs: implicit HTTP request context containing the authenticated User model.
     * Outputs: View instance populated with the authenticated user's details, predicted grades, history entries, and selectable supporting data for display.
     */
    public function __invoke(): View
    {
        /** @var User $user */
        $user = auth()->user();

        $predictedGrades = PredictedGrade::query()
            ->where('userID', $user->id)
            ->with('subject')
            ->orderByDesc('score')
            ->get();

        $historyEntries = HistoryEntry::query()
            ->where('userID', $user->id)
            ->with(['subject', 'type'])
            ->orderByDesc('studied_at')
            ->orderByDesc('score')
            ->get();

        $types = Type::query()
            ->whereRaw('LOWER(type) != ?', ['not studied'])
            ->orderBy('type')
            ->get();

        $subjects = Subject::query()
            ->whereIn('uuid', $predictedGrades->pluck('subjectID')->merge($historyEntries->pluck('subjectID'))->filter()->unique()->values())
            ->orderBy('name')
            ->get();

        return view('profile', [
            'user' => $user,
            'predictedGrades' => $predictedGrades,
            'historyEntries' => $historyEntries,
            'subjects' => $subjects,
            'types' => $types,
        ]);
    }
}
