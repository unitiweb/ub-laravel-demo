<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;

/**
 * The base model with common setup and methods for all models
 *
 * @package App\Models
 */
class BaseModel extends Model
{
    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'createdAt';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updatedAt';

    /**
     * The name of the "deleted at" column.
     *
     * @var string
     */
    const DELETED_AT = 'deletedAt';

//    /**
//     * Add any with relationships that may be in the request
//     *
//     * @param $query
//     */
//    public function scopeIncludeWith($query)
//    {
//        $with = Request::input('with');
//
//        if (is_string($with)) {
//            $with = array_map(function ($w) {
//                return trim($w);
//            }, explode(',', $with));
//        }
//
//        if (!empty($with) && is_array($with)) {
//            $query->with($with);
//        }
//    }

//    /**
//     * Scope a query to only include active users.
//     *
//     * @param  Builder  $query
//     *
//     * @return Builder
//     */
//    public function scopeSiteOnly($query)
//    {
//        $siteId = UserService::getSiteId();
//
//        return $query->where('siteId', $siteId);
//    }
}
