<?php

namespace App\Http\Resources;

use App\Facades\Services\BudgetStatsService;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BudgetStatsResource
 * @package App\Http\Resources
 */
class BudgetStatsResource extends JsonResource
{
    /**
     * An array that contains items to be shown
     *
     * @var array
     */
    protected $show;

    /**
     * BudgetStatsResource constructor
     *
     * @param Budget $budget
     * @param array $show
     */
    public function __construct(Budget $budget, array $show)
    {
        parent::__construct($budget);
        $this->show = $show;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'incomes' => $this->when($this->show['incomes'], BudgetStatsService::incomes($this->resource)),
        ];
    }
}
