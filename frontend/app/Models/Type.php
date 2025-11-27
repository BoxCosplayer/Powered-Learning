<?php

/**
 * Type model mapping weighting categories to their stored values.
 *
 * Inputs: hydrated database rows from the types table (uuid:string, type:string, weight:float).
 * Outputs: exposes persistence, relationships, and attribute casting for type records.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents a weighting category applied to study history entries.
 *
 * Inputs: none; instantiate via Eloquent retrieval or factory for testing.
 * Outputs: provides access to weight values and linked history entries.
 */
class Type extends Model
{

    /**
     * Table configuration for the types dataset.
     *
     * Inputs: none.
     * Outputs: informs Eloquent of table, key, and casting behaviour.
     *
     * @var string
     */
    protected $table = 'types';

    /**
     * Primary key name.
     *
     * Inputs: none.
     * Outputs: uuid string identifier for the row.
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
     * Outputs: boolean flag preventing timestamp columns.
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
        'type',
        'weight',
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
        'weight' => 'float',
    ];

    /**
     * History entries recorded with this type.
     *
     * Inputs: none.
     * Outputs: HasMany relation for linked HistoryEntry models.
     */
    public function historyEntries(): HasMany
    {
        return $this->hasMany(HistoryEntry::class, 'typeID', 'uuid');
    }
}
