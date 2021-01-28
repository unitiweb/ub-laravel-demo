<?php

namespace App\Models;

use App\Facades\Services\AuthService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * The site model
 *
 * @package App\Models
 */
class Settings extends BaseModel
{
    /**
     * Enables soft delete functionality
     */
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userId',
        'siteId',
        'view',
        'month',
        'institution',
        'account',
    ];
}
