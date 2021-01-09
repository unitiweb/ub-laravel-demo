<?php

namespace App\Models;

use App\Services\UserService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class BankInstitutionDetail
 *
 * @package App\Models
 *
 * @property mixed id
 */
class BankInstitutionDetail extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bankInstitutionDetails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institutionId',
        'name',
        'url',
        'logo',
        'primaryColor',
        'mediaPermission',
    ];
}
