<?php

namespace App\Facades\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * StringService facade
 *
 * @package App\Facades\Services
 *
 * @method static Collection parseBase64Image(string $base64Image)
 */
class StringService extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\StringService::class;
    }
}
