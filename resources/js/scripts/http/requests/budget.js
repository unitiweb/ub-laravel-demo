import { request } from '@/scripts/http/utils';

/**
 * Budget Requests
 */

export default {
    // Get a budget and any related data required
    getBudget: (month, relations) => {
        return request('get', ['budgets', month], {}, relations)
    },

    // Create a new budget
    addBudget: (month, relations) => {
        return request('post', ['budgets'], { month }, relations)
    },

    // Delete the budget
    deleteBudget: (month) => {
        return request('delete', ['budgets', month])
    },

    budgetStats: (month, params) => {
        return request('get', ['budgets', month, 'stats'], params)
    }
}
