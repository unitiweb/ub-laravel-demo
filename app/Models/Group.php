<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static siteOnly()
 */
class Group extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
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
     * Get all entries that belong to this group
     *
     * @return HasMany
     */
    public function entries(): HasMany
    {
        return $this->hasMany(BudgetEntry::class, 'groupId')
            ->join('budgets', 'entries.budgetId', '=', 'budgets.id')
            ->orderby('dueDay', 'asc')
            ->orderBy('name', 'asc');
    }
}
