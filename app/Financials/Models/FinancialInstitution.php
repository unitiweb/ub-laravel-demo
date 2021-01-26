<?php

namespace App\Financials\Models;

/**
 * Class AccountBalances
 *
 * @package App\Financials\Models
 *
 * @property string institutionId
 * @property string name
 * @property string url
 * @property string logo
 * @property string primaryColor
 */
class FinancialInstitution extends FinancialModel
{
    /**
     * The allowed data keys and types
     * The is a key => value pair where the key is the data key and the value is the type
     *
     * Example:
     *  $key = ['title' => 'string'];
     *
     * @var array
     */
    protected $keys = [
        'institutionId' => 'string',
        'name' => 'string',
        'url' => 'string',
        'logo' => 'string',
        'primaryColor' => 'string',
    ];
}
