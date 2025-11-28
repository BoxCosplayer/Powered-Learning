<?php

/**
 * Controller handling creation of predicted grades submitted from the profile view.
 *
 * Inputs: HTTP requests from authenticated users containing subject and score fields.
 * Outputs: redirect responses guiding users back to the profile with feedback after persistence.
 */

namespace App\Http\Controllers;

use App\Models\PredictedGrade;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Accepts predicted grade submissions and ensures subjects exist before storing.
 */
class PredictedGradeController extends Controller
{
    /**
     * Persist a predicted grade for the authenticated user, creating the subject if it does not already exist.
     *
     * Inputs: Request payload containing subject_name (string) and predicted_score (float) for the logged-in user.
     * Outputs: RedirectResponse back to the profile page with a success flash message after saving.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $validated = $request->validate([
            'subject_name' => ['required', 'string', 'max:255'],
            'predicted_score' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $subjectName = trim($validated['subject_name']);

        $subject = Subject::query()
            ->whereRaw('LOWER(name) = ?', [strtolower($subjectName)])
            ->first();

        if ($subject === null) {
            $subject = Subject::create([
                'uuid' => (string) Str::uuid(),
                'name' => $subjectName,
            ]);
        }

        PredictedGrade::create([
            'predictedGradeID' => (string) Str::uuid(),
            'userID' => $user->id,
            'subjectID' => $subject->uuid,
            'score' => $validated['predicted_score'],
        ]);

        return redirect()
            ->route('profile')
            ->with('status', 'Predicted grade saved successfully.');
    }
}
