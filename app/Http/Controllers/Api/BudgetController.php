<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiAbort;
use App\Facades\Services\BudgetService;
use App\Http\Requests\Api\BudgetStoreRequest;
use App\Http\Resources\BudgetResource;
use App\Models\Budget;
use App\Models\BudgetEntry;
use App\Models\BudgetGroup;
use App\Models\BudgetIncome;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Controller to handle budget resources
 *
 * @package App\Http\Controllers\Api
 */
class BudgetController extends ApiController
{
    /**
     * Get the budget with incomes and/or groups
     *
     * If the with query parameter is may include
     *    incomes : include the incomes and it's relationships
     *    groups  : income the budget groups and it's relationships
     *
     * @param Budget $budget The budget found by the budget date
     *
     * @return BudgetResource
     * @throws AuthorizationException
     */
    public function show(Budget $budget): BudgetResource
    {
        $this->authorize('view', $budget);
        $this->addBudgetWiths($budget);

        // Get budget incomes to be set as additional data
        $incomes = BudgetIncome::select('id', 'name', 'dueDay', 'amount')
            ->where('budgetId', $budget->id)
            ->orderBy('dueDay')
            ->get();

        // Get budget groups to be set as additional data
        $groups = BudgetService::getGroupsSortedList($budget);

        return (new BudgetResource($budget))->additional([
            'groups' => $groups,
            'incomes' => $incomes,
        ]);
    }

    /**
     * Create a new budget
     *
     * @param BudgetStoreRequest $request The request object
     *
     * @return BudgetResource
     * @throws BudgetResource
     */
    public function store(BudgetStoreRequest $request): BudgetResource
    {
        $data = $request->validated();
        $month = (new Carbon($data['month']))->startOfMonth();

        $budget = BudgetService::addBudget($month);
        $this->addBudgetWiths($budget);

        return new BudgetResource($budget);
    }

    /**
     * Create a new budget
     *
     * @param Request $request The request object
     * @param Budget $budget The budget found by the budget date
     *
     * @return Response
     * @throws AuthorizationException
     * @throws ApiAbort
     */
    public function destroy(Request $request, Budget $budget): Response
    {
        $this->authorize('destroy', $budget);

        try {
            // Remove the group from all entries on the budget
            DB::transaction(function () use ($budget) {
                BudgetEntry::where('budgetId', $budget->id)->delete();
                BudgetIncome::where('budgetId', $budget->id)->delete();
                BudgetGroup::where('budgetId', $budget->id)->delete();
                Budget::where('id', $budget->id)->delete();
                $budget->delete();
            });
        } catch (Exception $ex) {
            throw new ApiAbort($ex->getCode(), $ex->getMessage());
        }

        return response()->noContent();
    }

    /**
     * Add the incomes and/or groups if the with query params are included
     *
     * @param Budget $budget
     *
     * @return void
     */
    protected function addBudgetWiths(Budget $budget): void
    {
        $with = $this->getWith(['incomes', 'groups']);

        if ($with->contains('incomes')) {
            $budget->load([
                'incomes',
                'incomes.entries',
                'incomes.entries.income',
                'incomes.entries.group',
                'incomes.entries.transactions',
                'unassignedIncomeEntries',
            ]);
        }

        if ($with->contains('groups')) {
            $budget->load([
                'groups',
                'groups.entries',
                'groups.entries.income',
                'groups.entries.group',
                'groups.entries.transactions',
                'unassignedGroupEntries',
            ]);
        }
    }
}
