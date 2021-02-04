<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BankTransactionLink
 *
 * @package App\Models
 *
 * @property integer id
 * @property integer siteId
 * @property string entryName
 * @property string entryAmount
 * @property string transactionName
 * @property string transactionAmount
 * @property string match
 */
class BankTransactionLink extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bankTransactionLink';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'budgetId',
        'budgetEntryId',
        'bankTransactionId',
    ];

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
     * Get the parent budget
     *
     * @return BelongsTo
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budgetId');
    }

    /**
     * Get the parent budget
     *
     * @return BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(BankTransaction::class, 'bankTransactionId');
    }
}
