<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Settings resource class to display settings
 *
 * @package App\Http\Resources
 *
 * @property string view
 * @property string month
 * @property mixed institution
 * @property mixed account
 */
class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'view' => $this->view,
            'month' => $this->month,
            'institution' => $this->institution ? (int)$this->institution : null,
            'account' => $this->account ? (int)$this->account : null,
        ];
    }
}
