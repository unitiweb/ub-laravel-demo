<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionEntry extends BaseModel
{
    /**
     * Enables soft delete functionallity
     */
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactionEntries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'transactionId',
        'transactionDate',
        'type',
        'amount',
        'description',
    ];

    /**
     * When getting the transaction date convert to a carbon date
     *
     * @return Carbon
     * @throws Exception
     */
    public function getTransactionDateAttribute(): Carbon
    {
        return new Carbon($this->attributes['transactionDate']);
    }

    /**
     * The the site for this transaction
     *
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'siteId');
    }

    /**
     * The the site for this transaction
     *
     * @return BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transactionId');
    }
}
