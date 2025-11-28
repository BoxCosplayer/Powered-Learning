<?php

/**
 * Feature tests validating access control and rendering of the profile page.
 *
 * Inputs: HTTP GET requests targeting the profile route under different authentication states.
 * Outputs: assertions confirming redirects for guests and visible user details for logged-in users.
 */

namespace Tests\Feature;

use App\Models\HistoryEntry;
use App\Models\User;
use App\Models\Subject;
use App\Models\PredictedGrade;
use App\Models\Type;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Ensures the profile page is protected and displays account data for authenticated users.
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guests should be redirected to the login page when accessing the profile.
     *
     * Inputs: GET request to /profile without an authenticated session.
     * Outputs: redirect response pointing to the login route.
     */
    public function test_guest_is_redirected_to_login_when_accessing_profile(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect(route('login'));
    }

    /**
     * Authenticated users can view the profile page and see their details.
     *
     * Inputs: GET request to /profile with an authenticated user context.
     * Outputs: successful response containing profile headings and user fields.
     */
    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSeeText('Profile overview');
        $response->assertSeeText('Ada Lovelace');
        $response->assertSeeText('ada@example.com');
    }

    /**
     * Authenticated users can submit a predicted grade and have the subject created when it is new.
     *
     * Inputs: POST request to /profile/predicted-grades with subject_name and predicted_score for an authenticated user.
     * Outputs: redirect back to the profile with a persisted subject and predicted grade record tied to the user.
     */
    public function test_predicted_grade_submission_creates_subject_and_grade(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/profile/predicted-grades', [
            'subject_name' => 'Physics',
            'predicted_score' => 88.25,
        ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('status', 'Predicted grade saved successfully.');

        $subject = Subject::where('name', 'Physics')->first();

        $this->assertNotNull($subject);
        $this->assertDatabaseHas('predictedGrades', [
            'userID' => $user->id,
            'subjectID' => $subject?->uuid,
            'score' => 88.25,
        ]);
    }

    /**
     * Authenticated users should see only their own predicted grades on the profile page.
     *
     * Inputs: GET request to /profile with predicted grades for the authenticated user and another user.
     * Outputs: response containing the authenticated user's subject names and scores while omitting others' data.
     */
    public function test_profile_lists_only_authenticated_users_predicted_grades(): void
    {
        $user = User::factory()->create([
            'name' => 'Grace Hopper',
        ]);
        $otherUser = User::factory()->create();

        $maths = Subject::factory()->create(['name' => 'Mathematics']);
        $history = Subject::factory()->create(['name' => 'History']);
        $physics = Subject::factory()->create(['name' => 'Physics']);

        PredictedGrade::factory()->create([
            'userID' => $user->id,
            'subjectID' => $maths->uuid,
            'score' => 91.5,
        ]);

        PredictedGrade::factory()->create([
            'userID' => $user->id,
            'subjectID' => $history->uuid,
            'score' => 78.25,
        ]);

        PredictedGrade::factory()->create([
            'userID' => $otherUser->id,
            'subjectID' => $physics->uuid,
            'score' => 65.0,
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSeeText('Mathematics');
        $response->assertSeeText('91.5');
        $response->assertSeeText('History');
        $response->assertSeeText('78.25');
        $response->assertDontSee('Physics');
        $response->assertDontSee('65.0');
    }

    /**
     * Authenticated users should see only their own history entries on the profile page.
     *
     * Inputs: GET request to /profile with history entries for the authenticated user and another user.
     * Outputs: response containing only the authenticated user's subjects and scores from the history table.
     */
    public function test_profile_lists_only_authenticated_users_history_entries(): void
    {
        $user = User::factory()->create([
            'name' => 'Alan Turing',
        ]);
        $otherUser = User::factory()->create();

        $type = Type::create([
            'uuid' => Str::uuid()->toString(),
            'type' => 'Exam',
            'weight' => 1.0,
        ]);
        Type::create([
            'uuid' => Str::uuid()->toString(),
            'type' => 'Not studied',
            'weight' => 0.0,
        ]);
        $subjectMaths = Subject::factory()->create(['name' => 'Mathematics']);
        $subjectChemistry = Subject::factory()->create(['name' => 'Chemistry']);
        $subjectPhysics = Subject::factory()->create(['name' => 'Physics']);

        HistoryEntry::factory()->create([
            'userID' => $user->id,
            'subjectID' => $subjectMaths->uuid,
            'typeID' => $type->uuid,
            'score' => 84.2,
        ]);

        HistoryEntry::factory()->create([
            'userID' => $user->id,
            'subjectID' => $subjectChemistry->uuid,
            'typeID' => $type->uuid,
            'score' => 76.9,
        ]);

        HistoryEntry::factory()->create([
            'userID' => $otherUser->id,
            'subjectID' => $subjectPhysics->uuid,
            'typeID' => $type->uuid,
            'score' => 63.5,
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSee('value="Mathematics"', false);
        $response->assertSee('value="84.20"', false);
        $response->assertSee('value="Chemistry"', false);
        $response->assertSee('value="76.90"', false);
        $response->assertDontSee('Physics');
        $response->assertDontSee('63.5');
        $response->assertDontSee('Not studied');
    }

    /**
     * Authenticated users can create history entries from the profile page with automatic subject creation.
     *
     * Inputs: POST request to /profile/history with subject_name, type_id, score, and studied_at for the logged-in user.
     * Outputs: redirect back to the profile with a persisted history row tied to the user and linked subject.
     */
    public function test_history_entry_submission_creates_subject_and_entry(): void
    {
        $user = User::factory()->create();
        $type = Type::create([
            'uuid' => Str::uuid()->toString(),
            'type' => 'Mock Exam',
            'weight' => 1.0,
        ]);

        $response = $this->actingAs($user)->post('/profile/history', [
            'subject_name' => 'Biology',
            'type_id' => $type->uuid,
            'score' => 72.5,
            'studied_at' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHas('status', 'History entry saved successfully.');

        $subject = Subject::where('name', 'Biology')->first();

        $this->assertNotNull($subject);
        $this->assertDatabaseHas('history', [
            'userID' => $user->id,
            'subjectID' => $subject?->uuid,
            'typeID' => $type->uuid,
            'score' => 72.5,
        ]);
    }

    /**
     * Authenticated users can update and delete their own history entries from the profile page.
     *
     * Inputs: PUT and DELETE requests targeting /profile/history/{historyEntry} for an authenticated user's entry.
     * Outputs: updated database record reflecting new subject, type, score, and timestamp, followed by successful deletion.
     */
    public function test_history_entry_can_be_updated_and_deleted_from_profile(): void
    {
        $user = User::factory()->create();

        $originalType = Type::create([
            'uuid' => Str::uuid()->toString(),
            'type' => 'Quiz',
            'weight' => 0.5,
        ]);
        $updatedType = Type::create([
            'uuid' => Str::uuid()->toString(),
            'type' => 'Exam',
            'weight' => 0.75,
        ]);

        $originalSubject = Subject::factory()->create(['name' => 'English']);
        $historyEntry = HistoryEntry::factory()->create([
            'userID' => $user->id,
            'subjectID' => $originalSubject->uuid,
            'typeID' => $originalType->uuid,
            'score' => 55.5,
            'studied_at' => now()->subDays(2)->toDateString(),
        ]);

        $updatePayload = [
            'subject_name' => 'English Literature',
            'type_id' => $updatedType->uuid,
            'score' => 68.75,
            'studied_at' => now()->toDateString(),
        ];

        $updateResponse = $this->actingAs($user)->put("/profile/history/{$historyEntry->historyEntryID}", $updatePayload);

        $updateResponse->assertRedirect(route('profile'));
        $updateResponse->assertSessionHas('status', 'History entry updated successfully.');

        $updatedSubject = Subject::where('name', 'English Literature')->first();

        $this->assertNotNull($updatedSubject);
        $this->assertDatabaseHas('history', [
            'historyEntryID' => $historyEntry->historyEntryID,
            'userID' => $user->id,
            'subjectID' => $updatedSubject?->uuid,
            'typeID' => $updatedType->uuid,
            'score' => 68.75,
        ]);

        $deleteResponse = $this->actingAs($user)->delete("/profile/history/{$historyEntry->historyEntryID}");

        $deleteResponse->assertRedirect(route('profile'));
        $deleteResponse->assertSessionHas('status', 'History entry removed successfully.');
        $this->assertDatabaseMissing('history', [
            'historyEntryID' => $historyEntry->historyEntryID,
        ]);
    }
}
