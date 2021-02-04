<?php

namespace App\Http\Controllers\Api;

use App\Facades\Services\AuthService;
use App\Facades\Services\BudgetEntryService;
use App\Facades\Services\BudgetGroupService;
use App\Http\Requests\Api\BudgetEntryUpdateRequest;
use App\Http\Resources\BudgetEntryResource;
use App\Models\BankTransaction;
use App\Models\Budget;
use App\Models\BudgetEntry;
use App\Models\BudgetGroup;
use App\Models\BudgetIncome;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * Controller to handle budget entry resources
 *
 * @package App\Http\Controllers\Api
 */
class BudgetEntryController extends ApiController
{
    /**
     * Get a list of entries for the current budget
     *
     * @param Budget $budget The current budget
     *
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Budget $budget): AnonymousResourceCollection
    {
        $this->authorize('view', $budget);

        $entries = BudgetEntry::with('group', 'income', 'matches')
            ->where('budgetId', $budget->id)
            ->orderBy('dueDay', 'asc')
            ->get();

        return BudgetEntryResource::collection($entries);
    }

    /**
     * Get a single budget entry
     *
     * @param Budget $budget The current budget
     * @param BudgetEntry $entry The given budget entry
     *
     * @return BudgetEntryResource
     *
     * @throws AuthorizationException
     */
    public function show(Budget $budget, BudgetEntry $entry): BudgetEntryResource
    {
        $this->authorize('view', $budget);
        $this->authorize('view', $entry);
        $entry->load('group', 'income');

        return new BudgetEntryResource($entry);
    }

    /**
     * Create a new budget entry
     *
     * @param BudgetEntryUpdateRequest $request
     * @param Budget $budget The current budget
     *
     * @return BudgetEntryResource
     * @throws AuthorizationException
     */
    public function store(BudgetEntryUpdateRequest $request, Budget $budget): BudgetEntryResource
    {
        $this->authorize('store', $budget);
        $data = $request->validated();

        // If budgetIncomeId is given then make sure the budget income actually exists
        if (isset($data['budgetIncomeId'])) {
            BudgetIncome::where('id', $data['budgetIncomeId'])->firstOrFail();
        }

        // If budgetGroupId is given the make sure the budget group actually exists
        if (isset($data['budgetGroupId'])) {
            BudgetGroup::where('id', $data['budgetGroupId'])->firstOrFail();
        }

        // Create the budget entry
        $data['budgetId'] = $budget->id;
        $data['siteId'] = AuthService::getSite()->id;
        $entry = BudgetEntry::create($data);

        // If groupId is given the make sure the group actually exists
        if (isset($data['groupId'])) {
            BudgetGroupService::setBudgetGroup($budget, $entry, $data['groupId']);
            // Save the budget entry after the group was created
            $entry->save();
        }

        // Load any relations that are include in the with parameter
        $entry->load($this->getWith(['income', 'group', 'transactions'])->toArray());

        return new BudgetEntryResource($entry);
    }

    /**
     * Update the given entry
     *
     * @param BudgetEntryUpdateRequest $request
     * @param Budget $budget The current budget
     * @param BudgetEntry $entry The entry belonging to the current budget
     *
     * @return BudgetEntryResource
     * @throws AuthorizationException
     */
    public function update(BudgetEntryUpdateRequest $request, Budget $budget, BudgetEntry $entry): BudgetEntryResource
    {
        $this->authorize('view', $budget);
        $this->authorize('update', $entry);
        $data = $request->validated();

        DB::transaction(function () use ($data, $budget, $entry) {
            // Check if the budget income exists
            if (array_key_exists('budgetIncomeId', $data)) {
                if ($data['budgetIncomeId'] !== null) {
                    BudgetIncome::where('budgetId', $budget->id)
                        ->where('id', $data['budgetIncomeId'])
                        ->firstOrFail();
                }
            }

            // Check if the budget group exists
            if (array_key_exists('budgetGroupId', $data)) {
                if ($data['budgetGroupId'] !== null) {
                    BudgetGroup::where('budgetId', $budget->id)->where('id', $data['budgetGroupId'])->firstOrFail();
                }
            } elseif (array_key_exists('groupId', $data)) {
                BudgetGroupService::setBudgetGroup($budget, $entry, $data['groupId']);
            }

            // If the order needs to be updated do it now
            if (isset($data['order'])) {
                BudgetEntryService::setOrder($entry, $data['order']);
            }

            // Update the goal, paid, and cleared state
            BudgetEntryService::setStatus($entry, $data);

            // Update the entry
            $entry->update($data);

            // After all is save and if a bank transaction id exists
            // Try to create or update the entry transaction link
            if (array_key_exists('bankTransactionId', $data)) {
                if ($data['bankTransactionId'] === null) {
                    BudgetEntryService::unlinkEntryTransaction($budget, $entry);
                } else {
                    // Since a bank transaction id is given we need to create a match
                    // Get the bank transaction
                    $bankTransaction = BankTransaction::where('siteId', AuthService::getSite()->id)
                        ->where('id', $data['bankTransactionId'])
                        ->first();

                    BudgetEntryService::linkEntryTransaction($budget, $entry, $bankTransaction);
                }
            }

            // Clean up budget groups by removed any budget groups
            // that don't have a corresponding entry linked to it
            BudgetGroupService::cleanBudgetGroups($budget);
        });

        // Load any relations that are include in the with parameter
        $entry->load($this->getWith(['income', 'group', 'transactions'])->toArray());

        return new BudgetEntryResource($entry);
    }

    /**
     * Delete the given entry
     *
     * @param Budget $budget The current budget
     * @param BudgetEntry $entry The entry linked to the current budget
     *
     * @return Response
     * @throws Exception
     */
    public function destroy(Budget $budget, BudgetEntry $entry): Response
    {
        $this->authorize('view', $budget);
        $this->authorize('destroy', $entry);

        $entry->delete();

        return response()->noContent();
    }
}
