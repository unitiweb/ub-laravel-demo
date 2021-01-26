<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Model for the budgetIncomes table
 *
 * @package App\Models
 *
 * @property int id
 * @property int siteId
 */
class BudgetIncome extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'budgetIncomes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'budgetId',
        'name',
        'dueDay',
        'amount',
    ];

    /**
     * Get the parent budget
     *
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'siteId');
    }

    /**
     * Get the parent budget
     *
     * @return BelongsTo
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budgetId');
    }

    /**
     * Get the list of groups that belong to this income
     *
     * @return HasMany
     */
    public function entries(): HasMany
    {
        return $this->hasMany(BudgetEntry::class, 'budgetIncomeId')
            ->orderby('dueDay', 'asc')
            ->orderBy('name', 'asc');
    }
}
