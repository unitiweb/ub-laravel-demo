<?php


namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetGroupResource extends JsonResource
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
//            'siteId' => $this->siteId,
//            'budgetId' => $this->budgetId,
            'groupId' => $this->groupId,
            'name' => $this->name,
            'order' => $this->order,
//            'site' => new SiteResource($this->whenLoaded('site')),
//            'budget' => new BudgetResource($this->whenLoaded('budget')),
//            'group' => new GroupResource($this->whenLoaded('group')),
            'entries' => BudgetEntryResource::collection($this->whenLoaded('entries')),
        ];
    }

}
