<?php

/**
 * Controller coordinating study generation requests and surfacing queued results to authenticated users.
 *
 * Inputs: HTTP requests carrying tuning parameters and job identifiers for recommendation runs.
 * Outputs: redirect responses, rendered study views, and JSON payloads describing job statuses.
 */

namespace App\Http\Controllers;

use App\Jobs\RunRecommendation;
use App\Models\HistoryEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Handles orchestration of recommendation jobs and status retrieval.
 */
class StudyController extends Controller
{
    /**
     * Validate dashboard input, dispatch a recommendation job, and redirect to the study view.
     *
     * Inputs: Request containing numeric tuning parameters for the recommender and authenticated user context.
     * Outputs: RedirectResponse pointing to the study page with a unique job identifier or a redirect to the
     * dashboard when no user is authenticated.
     */
    public function start(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user === null) {
            return redirect()->route('dashboard')->with('status', 'You must be signed in to start a study run.');
        }

        $validated = $request->validate($this->validationRules());
        $jobId = (string) Str::uuid();

        $payload = [
            'SESSION_TIME_MINUTES' => (int) $validated['SESSION_TIME_MINUTES'],
            'BREAK_TIME_MINUTES' => (int) $validated['BREAK_TIME_MINUTES'],
            'SESSION_COUNT' => (int) $validated['SESSION_COUNT'],
            'SHOTS' => (int) $validated['SHOTS'],
        ];

        Cache::put($this->cacheKey($jobId), ['status' => 'pending'], now()->addMinutes(10));
        RunRecommendation::dispatch($jobId, $payload, (string) $user->getAuthIdentifier());

        Session::put('study_job_id', $jobId);

        return redirect()->route('study.show');
    }

    /**
     * Display the study view for the requested job identifier.
     *
     * Inputs: $jobId string unique identifier for the queued recommendation run.
     * Outputs: View rendered with current job state for polling and presentation.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $jobId = $request->session()->get('study_job_id');
        if ($jobId === null) {
            return redirect()->route('dashboard')->with('status', 'No active study run found.');
        }

        $state = Cache::get($this->cacheKey($jobId), ['status' => 'pending']);

        return view('study', [
            'jobId' => $jobId,
            'state' => $state,
        ]);
    }

    /**
     * Provide the status of a queued recommendation run as JSON for frontend polling.
     *
     * Inputs: $jobId string unique identifier for the queued recommendation run.
     * Outputs: JsonResponse containing status, result, or error information.
     */
    public function status(Request $request): JsonResponse
    {
        $jobId = $request->session()->get('study_job_id');
        if ($jobId === null) {
            return response()->json(['status' => 'error', 'error' => 'No active study run.'], 404);
        }

        $state = Cache::get($this->cacheKey($jobId), ['status' => 'pending']);

        return response()->json($state);
    }

    /**
     * Update the studied_at date for a history entry owned by the authenticated user.
     *
     * Inputs: Request carrying either `historyEntryId` or `subject` to target the entry to update.
     * Outputs: JsonResponse confirming the update or describing the reason it could not be applied.
     */
    public function touchHistory(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            return response()->json(['status' => 'error', 'message' => 'You must be signed in to update history.'], 401);
        }

        $historyEntryId = (string) $request->input('historyEntryId', '');

        if ($historyEntryId === '') {
            return response()->json(['status' => 'error', 'message' => 'History entry id is required.'], 400);
        }

        $historyEntry = HistoryEntry::query()
            ->where('historyEntryID', $historyEntryId)
            ->where('userID', $user->getAuthIdentifier())
            ->first();

        if ($historyEntry === null) {
            return response()->json(['status' => 'error', 'message' => 'No matching history entry found to update.'], 404);
        }

        $historyEntry->studied_at = now()->toDateString();
        $historyEntry->save();

        return response()->json([
            'status' => 'ok',
            'historyEntryId' => $historyEntry->historyEntryID,
            'studied_at' => optional($historyEntry->studied_at)->toDateString(),
        ]);
    }

    /**
     * Define validation rules for recommendation tuning inputs.
     *
     * Inputs: none.
     * Outputs: array of Laravel validation rules keyed by expected request parameter names.
     *
     * @return array<string, array<int, string|int>>
     */
    private function validationRules(): array
    {
        return [
            'SESSION_TIME_MINUTES' => ['required', 'integer', 'min:1', 'max:240'],
            'BREAK_TIME_MINUTES' => ['required', 'integer', 'min:1', 'max:180'],
            'SESSION_COUNT' => ['required', 'integer', 'min:1', 'max:200'],
            'SHOTS' => ['required', 'integer', 'min:1', 'max:50'],
        ];
    }

    /**
     * Build the cache key for storing and retrieving job state.
     *
     * Inputs: $jobId string unique identifier for the queued recommendation run.
     * Outputs: cache key string.
     */
    private function cacheKey(string $jobId): string
    {
        return sprintf('recommendation:%s', $jobId);
    }
}
