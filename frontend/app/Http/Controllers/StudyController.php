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
use App\Models\User;
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
     * Display the study view with optional job state, queue, and generation controls.
     *
     * Inputs: Request carrying session data for any active job and the authenticated user.
     * Outputs: View rendered with current job state (idle or cached), queue preview, and tunable defaults.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user === null) {
            return redirect()->route('dashboard')->with('status', 'You must be signed in to view the study page.');
        }

        $jobId = $request->session()->get('study_job_id');
        $state = $jobId !== null
            ? Cache::get($this->cacheKey($jobId), ['status' => 'pending'])
            : ['status' => 'idle', 'message' => 'No active study run yet.'];

        return view('study', [
            'jobId' => $jobId,
            'state' => $state,
            'tunableParameters' => $this->defaultSessionParameters(),
            'queue' => $this->buildQueue($user),
        ]);
    }

    /**
     * Provide the status of a queued recommendation run as JSON for frontend polling.
     *
     * Inputs: $jobId string unique identifier for the queued recommendation run.
     * Outputs: JsonResponse containing status, result, queue snapshot, or error information.
     */
    public function status(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            return response()->json(['status' => 'error', 'error' => 'You must be signed in to check study status.'], 401);
        }

        $jobId = $request->session()->get('study_job_id');
        $state = $jobId !== null
            ? Cache::get($this->cacheKey($jobId), ['status' => 'pending'])
            : ['status' => 'idle', 'message' => 'No active study run.'];

        return response()->json(array_merge(
            $state,
            [
                'jobId' => $jobId,
                'queue' => $this->buildQueue($user),
            ]
        ));
    }

    /**
     * Return the current study queue derived from unstudied history entries.
     *
     * Inputs: Request carrying the authenticated user context.
     * Outputs: JsonResponse containing queue length, next subject metadata, and ordered entries.
     */
    public function queue(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            return response()->json(['status' => 'error', 'message' => 'You must be signed in to view your queue.'], 401);
        }

        return response()->json($this->buildQueue($user));
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
     * Provide the default values the study page should display for session tuning.
     *
     * Inputs: none.
     * Outputs: associative array containing tunable parameter metadata (key, label, helper copy, default numeric value).
     *
     * @return array<int, array<string, string|int>>
     */
    private function defaultSessionParameters(): array
    {
        return [
            [
                'key' => 'SESSION_TIME_MINUTES',
                'label' => 'Session time (minutes)',
                'helper' => 'Length of each focused session before a rest.',
                'value' => 45,
                'type' => 'number',
            ],
            [
                'key' => 'BREAK_TIME_MINUTES',
                'label' => 'Break time (minutes)',
                'helper' => 'Duration of the pause between study sessions.',
                'value' => 15,
                'type' => 'number',
            ],
            [
                'key' => 'SESSION_COUNT',
                'label' => 'Total sessions',
                'helper' => 'How many sessions to schedule for this run.',
                'value' => 8,
                'type' => 'number',
            ],
            [
                'key' => 'SHOTS',
                'label' => 'Shots',
                'helper' => 'Number of practice attempts per subject rotation.',
                'value' => 8,
                'type' => 'number',
            ],
        ];
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
     * Build the data structure representing the queued subjects still awaiting study.
     *
     * Inputs: $user User currently authenticated.
     * Outputs: array containing queue count, next subject detail, and ordered entries.
     *
     * @return array<string, mixed>
     */
    private function buildQueue(User $user): array
    {
        $entries = HistoryEntry::query()
            ->with('subject')
            ->where('userID', $user->getAuthIdentifier())
            ->whereYear('studied_at', 1900)
            ->orderBy('logged_at')
            ->get();

        $next = $entries->first();

        return [
            'count' => $entries->count(),
            'next' => $next
                ? [
                    'historyEntryId' => $next->historyEntryID,
                    'subject' => $next->subject?->name ?? 'Unknown subject',
                    'logged_at' => optional($next->logged_at)->toDateTimeString(),
                    'studied_at' => optional($next->studied_at)->toDateString(),
                ]
                : null,
            'entries' => $entries->map(static function (HistoryEntry $entry): array {
                return [
                    'historyEntryId' => $entry->historyEntryID,
                    'subject' => $entry->subject?->name ?? 'Unknown subject',
                    'logged_at' => optional($entry->logged_at)->toDateTimeString(),
                    'studied_at' => optional($entry->studied_at)->toDateString(),
                ];
            })->all(),
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
