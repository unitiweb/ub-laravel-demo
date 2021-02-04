<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BankTransactionMatch
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
class BankTransactionMatch extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bankTransactionMatch';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'bankAccountId',
        'entryName',
        'entryAmount',
        'transactionName',
        'transactionAmount',
        'match',
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
}
