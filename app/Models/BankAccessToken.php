<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BankAccessToken
 *
 * @package App\Models
 *
 * @property mixed id
 * @property mixed bankAccessTokenId
 * @property mixed siteId
 * @property mixed token
 * @property mixed itemId
 */
class BankAccessToken extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bankAccessTokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'requestId',
        'itemId',
        'token'
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
