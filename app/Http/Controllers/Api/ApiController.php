<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class ApiController extends Controller
{
    /**
     * If a with parameter exists then create a collection and return
     *
     * @param array $allowed An array of allowed with values
     *
     * @return Collection
     */
    protected function getWith(array $allowed): Collection
    {
        if (Request::has('with')) {
            $withs = explode(',', Request::input('with'));

            return collect($withs)
                ->map(function ($value) {
                    return trim($value);
                })->filter(function ($value) use ($allowed) {
                    return in_array($value, $allowed);
                });
        }

        return collect();
    }
}
