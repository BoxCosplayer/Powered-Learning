<?php

/**
 * Controller enabling authenticated users to manage their study history entries.
 *
 * Inputs: HTTP requests carrying history payloads from logged-in users.
 * Outputs: Redirect responses returning users to the profile with feedback after create, update, or delete actions.
 */

namespace App\Http\Controllers;

use App\Models\HistoryEntry;
use App\Models\Subject;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

/**
 * Handles CRUD operations for history entries owned by the authenticated user.
 */
class HistoryEntryController extends Controller
{
    /**
     * Store a new history entry for the current user, creating the subject when needed.
     *
     * Inputs: Request containing subject_name (string), type_id (uuid), score (float 0-100), studied_at (date string).
     * Outputs: RedirectResponse back to the profile with a flash status message after persistence.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $validated = $request->validate([
            'subject_name' => ['required', 'string', 'max:255'],
            'type_id' => [
                'required',
                'string',
                Rule::exists('types', 'uuid'),
            ],
            'score' => ['required', 'numeric', 'min:-1000', 'max:100'],
            'studied_at' => ['required', 'date'],
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

        $typeName = Type::query()->find($validated['type_id'])?->type;

        Log::info('Creating history entry', [
            'user_id' => $user->id,
            'subject' => $subjectName,
            'type_id' => $validated['type_id'],
            'type' => $typeName,
            'studied_at' => $validated['studied_at'],
            'score' => $validated['score'],
        ]);

        HistoryEntry::create([
            'historyEntryID' => (string) Str::uuid(),
            'userID' => $user->id,
            'subjectID' => $subject->uuid,
            'typeID' => $validated['type_id'],
            'score' => $validated['score'],
            'studied_at' => Carbon::parse($validated['studied_at'])->toDateString(),
        ]);

        return redirect()
            ->route('profile')
            ->with('status', 'History entry saved successfully.');
    }

    /**
     * Update an existing history entry belonging to the authenticated user.
     *
     * Inputs: Request containing subject_name (string), type_id (uuid), score (float 0-100), studied_at (date string), and the targeted HistoryEntry model.
     * Outputs: RedirectResponse back to the profile after the record is updated or a forbidden response when ownership fails.
     */
    public function update(Request $request, HistoryEntry $historyEntry): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($historyEntry->userID !== $user->id) {
            abort(403, 'You may only update your own history entries.');
        }

        $validated = $request->validate([
            'subject_name' => ['required', 'string', 'max:255'],
            'type_id' => [
                'required',
                'string',
                Rule::exists('types', 'uuid'),
            ],
            'score' => ['required', 'numeric', 'min:-1000', 'max:100'],
            'studied_at' => ['required', 'date'],
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

        $typeName = Type::query()->find($validated['type_id'])?->type;

        Log::info('Updating history entry', [
            'user_id' => $user->id,
            'history_entry_id' => $historyEntry->historyEntryID,
            'subject' => $subjectName,
            'type_id' => $validated['type_id'],
            'type' => $typeName,
            'studied_at' => $validated['studied_at'],
            'score' => $validated['score'],
        ]);

        $historyEntry->update([
            'subjectID' => $subject->uuid,
            'typeID' => $validated['type_id'],
            'score' => $validated['score'],
            'studied_at' => Carbon::parse($validated['studied_at'])->toDateString(),
        ]);

        return redirect()
            ->route('profile')
            ->with('status', 'History entry updated successfully.');
    }

    /**
     * Remove the specified history entry when it belongs to the authenticated user.
     *
     * Inputs: Request context carrying the authenticated user and the targeted HistoryEntry model.
     * Outputs: RedirectResponse back to the profile after deletion or a forbidden response when ownership fails.
     */
    public function destroy(Request $request, HistoryEntry $historyEntry): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($historyEntry->userID !== $user->id) {
            abort(403, 'You may only delete your own history entries.');
        }

        $historyEntry->delete();

        return redirect()
            ->route('profile')
            ->with('status', 'History entry removed successfully.');
    }
}
