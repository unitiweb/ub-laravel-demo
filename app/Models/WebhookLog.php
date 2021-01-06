<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Category
 *
 * @package App\Models
 *
 * @property mixed executedAt
 * @property Carbon|mixed availableAt
 * @property mixed|string message
 * @property int|mixed totalRecords
 * @property bool|mixed success
 * @property mixed siteId
 * @property mixed|string identifier
 */
class WebhookLog extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'webhookLog';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'siteId',
        'siteId',
        'identifier',
        'message',
        'executedAt',
        'availableAt',
        'totalRecords',
        'success',
];
}
