<?php

/**
 * HistoryEntry model recording study events against subjects and types for users.
 *
 * Inputs: hydrated database rows from the history table (historyEntryID:string, userID:int, subjectID:string, typeID:string, score:float, studied_at:date).
 * Outputs: exposes persistence, relationships, and casting for history records.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a single study history entry for a user.
 *
 * Inputs: none; instantiate via Eloquent retrieval or factory methods.
 * Outputs: provides access to score, timestamps, and related user, subject, and type.
 */
class HistoryEntry extends Model
{
    use HasFactory;

    /**
     * Table configuration for the history dataset.
     *
     * Inputs: none.
     * Outputs: informs Eloquent of table name, key configuration, and timestamps.
     *
     * @var string
     */
    protected $table = 'history';

    /**
     * Primary key name.
     *
     * Inputs: none.
     * Outputs: UUID identifier for the row.
     *
     * @var string
     */
    protected $primaryKey = 'historyEntryID';

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
        'historyEntryID',
        'userID',
        'subjectID',
        'typeID',
        'score',
        'studied_at',
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
        'studied_at' => 'date',
    ];

    /**
     * User who recorded the history entry.
     *
     * Inputs: none.
     * Outputs: BelongsTo relation pointing to the owning User model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID');
    }

    /**
     * Subject that the history entry refers to.
     *
     * Inputs: none.
     * Outputs: BelongsTo relation pointing to the Subject model.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subjectID', 'uuid');
    }

    /**
     * Type category applied to the history entry.
     *
     * Inputs: none.
     * Outputs: BelongsTo relation pointing to the Type model.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'typeID', 'uuid');
    }
}
