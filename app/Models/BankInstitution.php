<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class BankInstitution
 *
 * @package App\Models
 *
 * @property mixed id
 * @property mixed siteId
 * @property mixed bankInstitutionDetailId
 */
class BankInstitution extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bankInstitutions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'bankInstitutionDetailId',
    ];

    /**
     * Get the accounts that belong to this institution
     *
     * @return HasMany
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(BankAccount::class, 'bankInstitutionId')->orderBy('enabled', 'desc');
    }

    /**
     * Get the parent institution details
     *
     * @return BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(BankInstitutionDetail::class, 'bankInstitutionDetailId');
    }
}
