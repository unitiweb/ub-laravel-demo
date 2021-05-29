<?php

namespace App\Http\Controllers\Api;

use App\Facades\Services\BudgetStatsService;
use App\Http\Requests\Api\BudgetStatsRequest;
use App\Http\Resources\BudgetStatsResource;
use App\Models\Budget;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BudgetStatsController
 * @package App\Http\Controllers\Api
 */
class BudgetStatsController extends ApiController
{
    /**
     * Get various stats for the given budget
     *
     * @param BudgetStatsRequest $request
     * @param Budget $budget
     *
     * @return BudgetStatsResource
     * @throws AuthorizationException
     */
    public function index(BudgetStatsRequest $request, Budget $budget): BudgetStatsResource
    {
        $data = $request->validated();
        $this->authorize('view', $budget);

        return new BudgetStatsResource($budget, $data);
    }
}
