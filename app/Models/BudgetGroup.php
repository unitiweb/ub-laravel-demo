<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Budget group model for the budgetGroups table
 *
 * @property int groupId
 * @property int siteId
 */
class BudgetGroup extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'budgetGroups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'budgetId',
        'groupId',
        'name',
        'order',
    ];

    /**
     * Get the site for this group
     *
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'siteId');
    }

    /**
     * Get the budget for this budget group
     *
     * @return BelongsTo
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budgetId');
    }

    /**
     * Get the parent group
     *
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'groupId');
    }

    /**
     * Get all entries that belong to this group
     *
     * @return HasMany
     */
    public function entries(): HasMany
    {
        return $this->hasMany(BudgetEntry::class, 'budgetGroupId')
            ->orderBy('order', 'asc');
    }
}
