<?php

namespace App\Models;

use App\Facades\Services\AuthService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model for the BudgetEntries table
 *
 * @property int id
 * @property int siteId
 * @property int budgetIncomeId
 * @property int budgetGroupId
 * @property int|null bankTransactionLinkId
 * @property string name
 * @property bool goal
 * @property bool paid
 * @property bool cleared
 * @property float amount
 */
class BudgetEntry extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'budgetEntries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'budgetId',
        'budgetIncomeId',
        'budgetGroupId',
        'categoryId',
        'name',
        'dueDay',
        'amount',
        'autoPay',
        'goal',
        'paid',
        'cleared',
        'order',
        'transactionMatch',
        'url',
    ];

    /**
     * When getting the paid attribute make sure that if autoPay is true then paid is true
     *
     * @param $value
     *
     * @return bool
     */
    public function getPaidAttribute($value)
    {
        if ($this->autoPay === true) {
            return true;
        }

        return $value;
    }

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
     * Get the parent income
     *
     * @return BelongsTo
     */
    public function income(): BelongsTo
    {
        return $this->belongsTo(BudgetIncome::class, 'budgetIncomeId');
    }

    /**
     * Get the parent group
     *
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(BudgetGroup::class, 'budgetGroupId');
    }

    /**
     * Get the parent category
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categoryId');
    }

    /**
     * Get the transaction for this entry
     *
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransactionLink::class, 'budgetEntryId')
            ->join('bankTransactions', 'bankTransactionLink.bankTransactionId', '=', 'bankTransactions.id');
    }
}
