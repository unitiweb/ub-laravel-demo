import { request } from '@/scripts/http/utils';

/**
 * Budget Group Requests
 */

export default {
    // get a group
    getBudgetGroups: (month, relations) => {
        return request('get', ['budgets', month, 'groups'], {}, relations)
    },

    // add budget group
    addBudgetGroup: (month, group, relations) => {
        return request('post', ['budgets', month, 'groups'], group, relations)
    },

    // Update a budget group
    updateBudgetGroup: (month, budgetGroupId, budgetGroup, relations) => {
        return request('patch', ['budgets', month, 'groups', budgetGroupId], budgetGroup, relations)
    },

    // Order a budget group entries
    orderBudgetGroupEntries: (month, budgetGroupId, entryIds, relations) => {
        return request('patch', ['budgets', month, 'groups', budgetGroupId, 'order'], entryIds, relations)
    },

    // Update a budget group
    deleteBudgetGroup: (month, budgetGroupId, relations) => {
        return request('delete', ['budgets', month, 'groups', budgetGroupId], {}, relations)
    }

}
