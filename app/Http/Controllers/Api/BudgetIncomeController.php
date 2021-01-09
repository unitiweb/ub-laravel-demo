<?php

namespace App\Http\Controllers\Api;

use App\Facades\Services\AuthService;
use App\Http\Requests\Api\BudgetIncomeStoreRequest;
use App\Http\Requests\Api\BudgetIncomeUpdateRequest;
use App\Http\Resources\BudgetIncomeResource;
use App\Models\Budget;
use App\Models\BudgetEntry;
use App\Models\BudgetIncome;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * Controller to handle budget income resources
 *
 * @package App\Http\Controllers\Api
 */
class BudgetIncomeController extends ApiController
{
    /**
     * Get a list of all incomes for the current budget
     *
     * @param Budget $budget The current budget
     *
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Budget $budget): AnonymousResourceCollection
    {
        $this->authorize('view', $budget);

        $incomes = BudgetIncome::where('budgetId', $budget->id)->orderBy('dueDay', 'asc');

        $with = $this->getWith(['entries']);
        if ($with->contains('entries')) {
            $with->push('entries.group', 'entries.income');
            $incomes->with($with->toArray());
        }

        return BudgetIncomeResource::collection($incomes->get());
    }

    /**
     * Display a single income
     *
     * @param Budget $budget The current budget
     * @param BudgetIncome $income The supplied income
     *
     * @return BudgetIncomeResource
     * @throws AuthorizationException
     */
    public function show(Budget $budget, BudgetIncome $income): BudgetIncomeResource
    {
        $this->authorize('view', $budget);
        $this->authorize('view', $income);

        $with = $this->getWith(['entries']);
        if ($with->contains('entries')) {
            $with->push('entries.group', 'entries.income');
            $income->load($with->toArray());
        }

        return new BudgetIncomeResource($income);
    }

    /**
     * Create a new budget income for the current budget
     *
     * @param BudgetIncomeStoreRequest $request
     * @param Budget $budget The current budget
     *
     * @return BudgetIncomeResource
     * @throws AuthorizationException
     */
    public function store(BudgetIncomeStoreRequest $request, Budget $budget): BudgetIncomeResource
    {
        $this->authorize('store', $budget);

        $data = $request->validated();
        $data['budgetId'] = $budget->id;
        $data['siteId'] = AuthService::getSite()->id;

        $income = BudgetIncome::create($data);

        return new BudgetIncomeResource($income);
    }

    /**
     * Update the given budget income for the current budget
     *
     * @param BudgetIncomeUpdateRequest $request
     * @param Budget $budget The current budget
     * @param BudgetIncome $income The given budget income
     *
     * @return BudgetIncomeResource
     * @throws AuthorizationException
     */
    public function update(BudgetIncomeUpdateRequest $request, Budget $budget, BudgetIncome $income): BudgetIncomeResource
    {
        $this->authorize('update', $budget);
        $this->authorize('update', $income);

        $data = $request->validated();
        $income->update($data);

        return new BudgetIncomeResource($income);
    }

    /**
     * Delete the given income if it exists and belongs to the current budget
     *
     * @param Budget $budget The current budget
     * @param BudgetIncome $income The income to be deleted
     *
     * @return Response
     * @throws Exception
     */
    public function destroy(Budget $budget, BudgetIncome $income): Response
    {
        $this->authorize('destroy', $budget);
        $this->authorize('destroy', $income);

        // Un-assign all entries before we delete the income
        BudgetEntry::where('budgetId', $budget->id)
            ->where('budgetIncomeId', $income->id)
            ->update(['budgetIncomeId' => null]);

        $income->delete();

        return response()->noContent();
    }
}
