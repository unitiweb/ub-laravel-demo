<?php

namespace App\Http\Controllers\Api;

use App\Facades\Services\AuthService;
use App\Http\Requests\Api\BudgetGroupStoreRequest;
use App\Http\Requests\Api\BudgetGroupUpdateRequest;
use App\Http\Resources\BudgetGroupResource;
use App\Models\Budget;
use App\Models\BudgetEntry;
use App\Models\BudgetGroup;
use App\Models\Group;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * Controller to handle budget income resources
 *
 * @package App\Http\Controllers\Api
 */
class BudgetGroupController extends ApiController
{
    /**
     * Get a list of all groups for the current budget
     *
     * @param Budget $budget The current budget
     *
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Budget $budget): AnonymousResourceCollection
    {
        $this->authorize('view', $budget);

        $groups = BudgetGroup::where('budgetId', $budget->id)->orderBy('order', 'asc');

        $with = $this->getWith(['entries']);
        if ($with->contains('entries')) {
            $with->push('entries.group', 'entries.income');
            $groups->with($with->toArray());
        }

        return BudgetGroupResource::collection($groups->get());
    }

    /**
     * Display a single group
     *
     * @param Budget $budget The current budget
     * @param BudgetGroup $group The supplied group
     *
     * @return BudgetGroupResource
     * @throws AuthorizationException
     */
    public function show(Budget $budget, BudgetGroup $group): BudgetGroupResource
    {
        $this->authorize('view', $budget);
        $this->authorize('view', $group);

        $with = $this->getWith(['entries']);
        if ($with->contains('entries')) {
            $with->push('entries.group', 'entries.income');
            $group->load($with->toArray());
        }

        return new BudgetGroupResource($group);
    }

    /**
     * Create a new budget group for the current budget
     * If a similar (parent) site global group does not exist
     * Then, we'll create one and use that group id as the parent group id
     *
     * @param BudgetGroupStoreRequest $request
     * @param Budget $budget The current budget
     *
     * @return BudgetGroupResource
     * @throws AuthorizationException
     */
    public function store(BudgetGroupStoreRequest $request, Budget $budget): BudgetGroupResource
    {
        $this->authorize('store', $budget);

        $data = $request->validated();
        $siteId = AuthService::getSite()->id;

        // Check if a similar site global group exists
        // If it doesn't exist then create on with this new groups info also
        if (!$group = Group::where('siteId', $siteId)->where('name', $data['name'])->first()) {
            $order = Group::where('siteId', $siteId)->count();
            $group = Group::create([
                'siteId' => $siteId,
                'name' => $data['name'],
                'order' => $order,
            ]);
        }

        $group = BudgetGroup::create([
            'siteId' => $siteId,
            'budgetId' => $budget->id,
            'groupId' => $group->id,
            'name' => $data['name'],
            'order' => $group->order,
        ]);

        return new BudgetGroupResource($group);
    }

    /**
     * Update the given group
     *
     * @param BudgetGroupUpdateRequest $request
     * @param Budget $budget The current budget
     * @param BudgetGroup $group
     *
     * @return BudgetGroupResource
     * @throws AuthorizationException
     */
    public function update(BudgetGroupUpdateRequest $request, Budget $budget, BudgetGroup $group): BudgetGroupResource
    {
        $this->authorize('update', $budget);
        $this->authorize('update', $group);

        $data = $request->validated();
        $group->update($data);

        return new BudgetGroupResource($group);
    }

    /**
     * Delete the budget group
     *
     * @param Budget $budget The current budget
     * @param BudgetGroup $group The given budget group
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Budget $budget, BudgetGroup $group): Response
    {
        $this->authorize('destroy', $budget);
        $this->authorize('destroy', $group);
        $groupId = $group->groupId;

        // Remove all entries from the budget group
        BudgetEntry::where('budgetGroupId', $group->id)->update(['budgetGroupId' => null]);

        // Delete the budget group
        $group->delete();

        // Check if the parent group is a parent for any other budget groups
        // If there are none then delete the group also
        if (!BudgetGroup::where('groupId', $groupId)->exists()) {
            Group::where('id', $groupId)->delete();
        }

        return response()->noContent();
    }
}
