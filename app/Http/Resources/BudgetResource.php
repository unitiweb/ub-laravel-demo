<?php

namespace App\Http\Resources;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BudgetResource
 * @package App\Http\Resources
 */
class BudgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'month' => $this->month,
            'site' => new SiteResource($this->whenLoaded('site')),
            'incomes' => BudgetIncomeResource::collection($this->whenLoaded('incomes')),
            'groups' => BudgetGroupResource::collection($this->whenLoaded('groups')),
//            'entries' => BudgetEntryResource::collection($this->whenLoaded('entries')),
            'unassignedIncomeEntries' => BudgetEntryResource::collection($this->whenLoaded('unassignedIncomeEntries')),
            'unassignedGroupEntries' => BudgetEntryResource::collection($this->whenLoaded('unassignedGroupEntries')),
        ];
    }
}
