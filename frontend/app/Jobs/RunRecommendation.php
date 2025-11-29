<?php

/**
 * Queue job responsible for invoking the Python subject recommender and caching the result for polling.
 *
 * Inputs: job identifier string and validated payload array containing tuning parameters for the recommender.
 * Outputs: cached status map keyed by the job identifier describing pending, success, or error outcomes alongside any payload.
 */

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;
use Throwable;

/**
 * Executes the Python module within a queue worker to avoid blocking web requests.
 */
class RunRecommendation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance holding the payload and tracking identifier.
     *
     * Inputs: $jobId string unique identifier for this run; $payload array of validated recommender parameters;
     * $userId string identifier of the authenticated user to target within the recommender.
     * Outputs: initialises immutable properties used during queued execution.
     *
     * @param string $jobId
     * @param array<string, mixed> $payload
     * @param string $userId
     */
    public function __construct(public string $jobId, public array $payload, public string $userId)
    {
    }

    /**
     * Run the Python recommender, capture any output, and store the status in cache.
     *
     * Inputs: serialised properties hydrated by the queue worker.
     * Outputs: cache entry keyed by job id containing status, result data, or error detail.
     */
    public function handle(): void
    {
        $cacheKey = $this->cacheKey();
        Cache::put($cacheKey, ['status' => 'pending'], now()->addMinutes(10));

        try {
            $process = new Process($this->buildCommand(), base_path('..'));
            $process->setInput($this->encodePayload());
            $process->setTimeout(30);
            $process->run();

            if ($process->isSuccessful() === false) {
                $this->cacheError($cacheKey, trim($process->getErrorOutput() ?: $process->getOutput()));
                return;
            }

            $decoded = $this->decodeOutput($process->getOutput());
            Cache::put($cacheKey, ['status' => 'done', 'result' => $decoded], now()->addMinutes(10));
        } catch (Throwable $exception) {
            $this->cacheError($cacheKey, $exception->getMessage());
        }
    }

    /**
     * Build the command used to execute the recommender module.
     *
     * Inputs: none; relies on environment configuration for the recommender binary path when present and
     * the user identifier supplied during job dispatch.
     * Outputs: ordered list of process arguments for Symfony Process execution calling the CLI directly.
     *
     * @return list<string>
     */
    private function buildCommand(): array
    {
        $binaryPath = env('RECOMMENDER_BINARY', base_path('..\\.venv\\Scripts\\subject-recommender.exe'));

        return [
            $binaryPath,
            '-u',
            $this->userId,
            '-c',
            (string) $this->payload['SESSION_COUNT'],
            '-t',
            (string) $this->payload['SESSION_TIME_MINUTES'],
            '-b',
            (string) $this->payload['BREAK_TIME_MINUTES'],
            '-s',
            (string) $this->payload['SHOTS'],
            '-r',
        ];
    }

    /**
     * Encode the payload into JSON for stdin consumption by the Python module.
     *
     * Inputs: job payload array containing tuned parameters.
     * Outputs: JSON string suitable for process input.
     */
    private function encodePayload(): string
    {
        return json_encode($this->payload, JSON_THROW_ON_ERROR);
    }

    /**
     * Decode JSON output from the Python process into an array for caching.
     *
     * Inputs: raw stdout string from the Python invocation.
     * Outputs: associative array of recommender results, or a wrapped message when decoding fails.
     *
     * @param string $output
     * @return array<mixed>
     */
    private function decodeOutput(string $output): array
    {
        try {
            /** @var array<mixed> $decoded */
            $decoded = json_decode($output, true, 512, JSON_THROW_ON_ERROR);
            return $decoded;
        } catch (Throwable) {
            return ['message' => trim($output)];
        }
    }

    /**
     * Write an error state into cache for the given key.
     *
     * Inputs: $cacheKey string destination key; $message string human-readable failure description.
     * Outputs: cached map describing the error for frontend polling.
     */
    private function cacheError(string $cacheKey, string $message): void
    {
        Cache::put($cacheKey, ['status' => 'error', 'error' => $message], now()->addMinutes(10));
    }

    /**
     * Provide the cache key derived from the job identifier.
     *
     * Inputs: none.
     * Outputs: string cache key used to store and retrieve job state.
     */
    private function cacheKey(): string
    {
        return sprintf('recommendation:%s', $this->jobId);
    }

}
