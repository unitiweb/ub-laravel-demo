<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model for the budgets table
 *
 * @property int id
 * @property string month
 * @property int siteId
 * @property BudgetIncome incomes
 * @property BudgetGroup groups
 * @property Collection entries
 */
class Budget extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'month',
    ];

    /**
     * Resolve the route binding for the budget by month
     *
     * @param mixed $value The budget month date
     * @param null $field The field if supplied
     *
     * @return self|null
     */
    public function resolveRouteBinding($value, $field = null): self
    {
        try {
            $month = (new Carbon($value))->firstOfMonth();
        } catch (Exception $ex) {
            $month = null;
        }

        return $this->where('month', $month)->firstOrFail();
    }

    /**
     * The the site for this budget
     *
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'siteId');
    }

    /**
     * The budget incomes
     *
     * @return HasMany
     */
    public function incomes()
    {
        return $this->hasMany(BudgetIncome::class, 'budgetId')
            ->orderBy('dueDay', 'asc')
            ->orderBy('name', 'asc');
    }

    /**
     * The budget groups
     *
     * @return HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(BudgetGroup::class, 'budgetId')
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc');
    }

    /**
     * The budget entries
     *
     * @return HasMany
     */
    public function entries()
    {
        return $this->hasMany(BudgetEntry::class, 'budgetId')
            ->orderBy('dueDay', 'desc');
    }

    /**
     * Get the list of groups that belong to this income
     *
     * @return HasMany
     */
    public function unassignedIncomeEntries(): HasMany
    {
        return $this->hasMany(BudgetEntry::class, 'budgetId')
            ->whereNull('budgetIncomeId')
            ->orderby('dueDay', 'asc')
            ->orderBy('name', 'asc');
    }

    /**
     * Get the list of groups that belong to this income
     *
     * @return HasMany
     */
    public function unassignedGroupEntries(): HasMany
    {
        return $this->hasMany(BudgetEntry::class, 'budgetId')
            ->whereNull('budgetGroupId')
            ->orderby('dueDay', 'asc')
            ->orderBy('name', 'asc');
    }
}
