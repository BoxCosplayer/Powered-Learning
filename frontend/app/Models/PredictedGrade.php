<?php

/**
 * PredictedGrade model storing projected scores per user and subject.
 *
 * Inputs: hydrated database rows from the predictedGrades table (predictedGradeID:string, userID:int, subjectID:string, score:float).
 * Outputs: exposes persistence, relationships, and casting for predicted grade records.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a predicted score for a subject generated for a user.
 *
 * Inputs: none; instantiate via Eloquent retrieval or factory methods.
 * Outputs: provides access to predicted scores and the linked user and subject.
 */
class PredictedGrade extends Model
{
    use HasFactory;

    /**
     * Table configuration for the predictedGrades dataset.
     *
     * Inputs: none.
     * Outputs: informs Eloquent of table name, key configuration, and timestamps.
     *
     * @var string
     */
    protected $table = 'predictedGrades';

    /**
     * Primary key name.
     *
     * Inputs: none.
     * Outputs: UUID identifier for the row.
     *
     * @var string
     */
    protected $primaryKey = 'predictedGradeID';

    /**
     * Indicates the primary key is non-incrementing.
     *
     * Inputs: none.
     * Outputs: boolean flag guiding Eloquent key handling.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Primary key type for casting.
     *
     * Inputs: none.
     * Outputs: string indicating the key type.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates the model does not manage timestamps.
     *
     * Inputs: none.
     * Outputs: boolean disabling timestamp columns.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Mass-assignable attributes on the model.
     *
     * Inputs: none; list consumed by Eloquent during fill/create.
     * Outputs: list of attribute keys allowed for mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'predictedGradeID',
        'userID',
        'subjectID',
        'score',
    ];

    /**
     * Attribute casting rules.
     *
     * Inputs: none.
     * Outputs: map of attribute names to cast types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'float',
    ];

    /**
     * User who owns the predicted grade.
     *
     * Inputs: none.
     * Outputs: BelongsTo relation pointing to the owning User model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID');
    }

    /**
     * Subject the prediction relates to.
     *
     * Inputs: none.
     * Outputs: BelongsTo relation pointing to the Subject model.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subjectID', 'uuid');
    }
}
