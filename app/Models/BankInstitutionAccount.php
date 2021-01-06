<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class BankInstitutionAccount
 *
 * @package App\Models
 *
 * @property mixed id
 * @property mixed bankInstitutionId
 * @property mixed bankAccountId
 * @property mixed siteId
 */
class BankInstitutionAccount extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bankInstitutionAccounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'bankInstitutionId',
        'bankAccountId',
    ];

    /**
     * Get the institution
     *
     * @return BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(BankInstitution::class, 'bankInstitutionId');
    }

    /**
     * Get the institution
     *
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsto(BankAccount::class, 'bankAccountId');
    }
}
