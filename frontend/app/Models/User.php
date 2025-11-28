<?php

/**
 * User model representing application users authenticated via UUID identifiers.
 *
 * Inputs: hydrated database rows from the users table (id:uuid, name:string, email:string, password:string, timestamps).
 * Outputs: exposes authentication behaviours, notifications, and relationships via Eloquent.
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Authenticated user entity with UUID primary key and notification capabilities.
 *
 * Inputs: none; instantiated via Eloquent retrieval or factory methods.
 * Outputs: provides access to user attributes, authentication, and notification helpers.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

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
     * Primary key data type.
     *
     * Inputs: none.
     * Outputs: string indicating the key type.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * Inputs: none; list consumed by Eloquent during fill/create.
     * Outputs: list of attribute keys allowed for mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * Inputs: none.
     * Outputs: list of attributes excluded from array/json casting.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * Inputs: none.
     * Outputs: array map of attribute names to cast types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Predicted grades associated with the user.
     *
     * Inputs: none.
     * Outputs: HasMany relation targeting the PredictedGrade model using userID as the foreign key.
     */
    public function predictedGrades(): HasMany
    {
        return $this->hasMany(PredictedGrade::class, 'userID');
    }

    /**
     * History entries recorded by the user.
     *
     * Inputs: none.
     * Outputs: HasMany relation targeting the HistoryEntry model keyed by userID.
     */
    public function historyEntries(): HasMany
    {
        return $this->hasMany(HistoryEntry::class, 'userID');
    }
}
