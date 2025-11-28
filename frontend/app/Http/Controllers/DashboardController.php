<?php

/**
 * Controller presenting the authenticated dashboard for Powered Learning users.
 *
 * Inputs: HTTP requests passing through the auth middleware with an authenticated user context.
 * Outputs: HTML response rendering the dashboard Blade view populated with user details.
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

/**
 * Handles display of the dashboard for logged-in users.
 */
class DashboardController extends Controller
{
    /**
     * Render the dashboard view for the current authenticated user.
     *
     * Inputs: implicit HTTP request context containing the authenticated User model.
     * Outputs: View instance populated with the authenticated user's data.
     */
    public function __invoke(): View
    {
        /** @var User $user */
        $user = auth()->user();

        return view('dashboard', [
            'user' => $user,
            'configOptions' => $this->readPythonConfigOptions(),
        ]);
    }

    /**
     * Parse the Python configuration file so dashboard users can review and select values.
     *
     * Inputs: None; reads constants declared in src/subject_recommender/config.py.
     * Outputs: associative array grouped by category, each containing labelled options and type hints for form inputs.
     */
    private function readPythonConfigOptions(): array
    {
        $configPath = base_path(
            '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'subject_recommender' . DIRECTORY_SEPARATOR . 'config.py'
        );

        if (! file_exists($configPath)) {
            return [];
        }

        $constants = [];

        foreach (file($configPath) as $line) {
            $trimmed = trim($line);

            if ($trimmed === '' || str_starts_with($trimmed, '#') || str_starts_with($trimmed, '"""')) {
                continue;
            }

            if (! preg_match('/^([A-Z_]+)\\s*=\\s*(.+)$/', $trimmed, $matches)) {
                continue;
            }

            $key = $matches[1];
            $rawValue = preg_split('/\\s+#/', $matches[2])[0] ?? '';
            $value = $this->normalisePythonLiteral($rawValue);

            $constants[$this->mapOptionGroup($key)][] = [
                'key' => $key,
                'value' => $value,
                'type' => $this->inferFieldType($value),
            ];
        }

        return array_filter($constants, static fn(array $group) => ! empty($group));
    }

    /**
     * Clean up raw Python literals for safe display inside HTML form fields.
     *
     * Inputs: raw string fragment captured from the config file line.
     * Outputs: scalar value coerced to an int, float, or trimmed string where appropriate.
     */
    private function normalisePythonLiteral(string $rawValue): int|float|string
    {
        $value = rtrim(trim($rawValue), ',');
        $value = trim($value);

        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            return trim($value, "\"'");
        }

        if (is_numeric($value)) {
            return $value + 0;
        }

        return $value;
    }

    /**
     * Suggest the correct HTML input type for a given config value.
     *
     * Inputs: scalar config value already normalised.
     * Outputs: string representing the form field type ("number" or "text").
     */
    private function inferFieldType(int|float|string $value): string
    {
        return is_numeric($value) ? 'number' : 'text';
    }

    /**
     * Bucket config keys into themed groups for clearer dashboard presentation.
     *
     * Inputs: config constant name from the Python module.
     * Outputs: human-readable group label used in the Blade template.
     */
    private function mapOptionGroup(string $key): string
    {
        if (str_starts_with($key, 'DATE_WEIGHT')) {
            return 'Date weighting';
        }

        if (str_contains($key, 'WEIGHT')) {
            return 'Assessment weighting';
        }

        if (str_starts_with($key, 'SESSION') || str_ends_with($key, 'MINUTES') || $key === 'SHOTS') {
            return 'Session defaults';
        }

        if (str_starts_with($key, 'DATABASE')) {
            return 'Database defaults';
        }

        return 'Miscellaneous';
    }
}
