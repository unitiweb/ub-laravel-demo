<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class User
 *
 * @package App\Models
 *
 * @property int id
 * @property int siteId
 * @property string uid
 * @property string email
 * @property string password
 * @property bool verified
 * @property Settings settings
 * @property Site site
 * @property string avatar
 */
class User extends BaseModel
{
    /**
     * Enables soft delete functionality
     */
    use SoftDeletes;

    // Available role types
    const ROLE_OWNER = 'OWNER';
    const ROLE_MANAGER = 'MANAGER';
    const ROLE_USER = 'USER';

    // Available status types
    const STATUS_PENDING = 'PENDING';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_DISABLED = 'DISABLED';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'email',
        'emailChange',
        'password',
        'avatar',
        'verified',
        'firstName',
        'lastName',
        'role',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Hooks for model lifecycle
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uid = (string) Str::uuid();
        });
    }

    /**
     * The the user's site
     *
     * @return BelongsTo
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'siteId');
    }

    /**
     * Get the site's settings
     *
     * @return HasOne
     */
    public function settings(): HasOne
    {
        return $this->hasOne(Settings::class, 'userId');
    }
}
