<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed siteId
 * @property mixed bankAccountId
 * @property mixed available
 * @property mixed current
 * @property mixed limit
 * @property mixed isoCurrencyCode
 * @property mixed unofficialCurrencyCode
 */
class BankBalance extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bankBalances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'bankAccountId',
        'available',
        'current',
        'limit',
        'isoCurrencyCode',
        'unofficialCurrencyCode',
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
     * Get the parent bank account
     *
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bankAccountId');
    }
}
