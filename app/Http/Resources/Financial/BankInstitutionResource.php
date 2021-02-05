<?php

namespace App\Http\Resources\Financial;

use App\Http\Resources\SiteResource;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BankInstitutionResource
 *
 * @package App\Http\Resources
 *
 * @property mixed institutionId
 * @property mixed name
 * @property mixed url
 * @property mixed logo
 * @property mixed primaryColor
 */
class BankInstitutionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $institution = $this->resource->institution;

        return [
            'id' => $institution->id,
            'institutionId' => $institution->institutionId,
            'name' => $institution->name,
            'url' => $institution->url,
            'logo' => $institution->logo,
            'primaryColor' => $institution->primaryColor,
            'accounts' => BankAccountResource::collection($this->whenLoaded('accounts'))
        ];
    }
}
