<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class BankAccount
 *
 * @package App\Models
 *
 * @property int id
 * @property int siteId
 * @property int accountId
 * @property int bankAccessTokenId
 * @property string name
 * @property string officialName
 * @property string subType
 * @property string type
 * @property string mask
 * @property int bankInstitutionId
 * @property bool enabled
 */
class BankAccount extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bankAccounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'bankAccessTokenId',
        'institutionId',
        'accountId',
        'mask',
        'name',
        'officialName',
        'subType',
        'type',
        'enabled'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['balances'];

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
     * Get the account balances
     *
     * @return HasOne
     */
    public function balances(): HasOne
    {
        return $this->hasOne(BankBalance::class, 'bankAccountId');
    }

    /**
     * Get transactions for this account
     *
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class, 'bankAccountId');
    }

    /**
     * Get the parent institution
     *
     * @return BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(BankInstitution::class, 'bankInstitutionId');
    }

    /**
     * Get the bank access token for this bank account
     *
     * @return HasOne
     */
    public function bankAccessToken(): HasOne
    {
        return $this->hasOne(BankAccessToken::class, 'id', 'bankAccessTokenId');
    }
}
