<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class BankTransaction
 *
 * @package App\Models
 *
 * @property mixed id
 * @property mixed siteId
 * @property mixed bankAccessTokenId
 * @property mixed bankAccountId
 * @property mixed transactionId
 * @property mixed amount
 * @property mixed transactionDate
 * @property mixed paymentChannel
 * @property mixed isoCurrencyCode
 * @property mixed name
 * @property mixed pending
 * @property mixed categoryId
 * @property mixed category
 * @property bankAccount account
 */
class BankTransaction extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bankTransactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'bankAccessTokenId',
        'bankAccountId',
        'transactionId',
        'category',
        'amount',
        'transactionDate',
        'paymentChannel',
        'isoCurrencyCode',
        'name',
        'pending',
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
     * Get the related access token
     *
     * @return BelongsTo
     */
    public function accessToken(): BelongsTo
    {
        return $this->belongsTo(BankAccessToken::class, 'bankAccessTokenId');
    }

    /**
     * Get the related account
     *
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bankAccountId');
    }

    /**
     * Get the transaction's category
     *
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'categoryId');
    }
}
