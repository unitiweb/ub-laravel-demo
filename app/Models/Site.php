<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * The site model
 *
 * @package App\Models
 *
 * @property string id
 * @property string name
 */
class Site extends BaseModel
{
    /**
     * Enables soft delete functionallity
     */
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get all users for this site
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'siteId');
    }

    /**
     * Get all budgets for this site
     *
     * @return HasMany
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'siteId');
    }
}
