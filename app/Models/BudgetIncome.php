<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model for the budgetIncomes table
 *
 * @package App\Models
 *
 * @property int id
 * @property int siteId
 * @property int budgetId
 * @property int bankTransactionLinkId
 * @property string name
 * @property double amount
 * @property Collection entries
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

    /**
     * Get the transaction for this entry
     *
     * @return HasOne
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(BankTransactionLink::class, 'budgetIncomeId')
            ->join('bankTransactions', 'bankTransactionLink.bankTransactionId', '=', 'bankTransactions.id');
    }
}
