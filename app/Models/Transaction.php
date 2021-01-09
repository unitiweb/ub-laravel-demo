<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Transaction extends BaseModel
{
    /**
     * Enables soft delete functionallity
     */
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
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
     * The the site for this budget
     *
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'siteId');
    }

    /**
     * Get a list of transaction entries
     *
     * @return HasMany
     */
    public function transactionEntries(): HasMany
    {
        return $this->hasMany(TransactionEntry::class, 'transactionId');
    }
}
