<?php

/**
 * Subject model representing study areas available for recommendations.
 *
 * Inputs: hydrated database rows from the subjects table (uuid:string, name:string).
 * Outputs: exposes persistence, relationships, and casts for subject records.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents a subject that users study and receive scores for.
 *
 * Inputs: none; instantiated via Eloquent retrieval or factory methods.
 * Outputs: provides access to subject attributes and related history or predictions.
 */
class Subject extends Model
{
    use HasFactory;

    /**
     * Table configuration for the subjects dataset.
     *
     * Inputs: none.
     * Outputs: informs Eloquent of table name, key configuration, and timestamps.
     *
     * @var string
     */
    protected $table = 'subjects';

    /**
     * Primary key name.
     *
     * Inputs: none.
     * Outputs: uuid identifier for the row.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

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
        'uuid',
        'name',
    ];

    /**
     * Predicted grades for this subject.
     *
     * Inputs: none.
     * Outputs: HasMany relation for linked PredictedGrade models.
     */
    public function predictedGrades(): HasMany
    {
        return $this->hasMany(PredictedGrade::class, 'subjectID', 'uuid');
    }

    /**
     * Historical study entries for this subject.
     *
     * Inputs: none.
     * Outputs: HasMany relation for linked HistoryEntry models.
     */
    public function historyEntries(): HasMany
    {
        return $this->hasMany(HistoryEntry::class, 'subjectID', 'uuid');
    }
}
