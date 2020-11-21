import { request } from '@/scripts/http/utils';

/**
 * Budget Income Requests
 */

export default {
    // Create a new budget income
    getIncomes: (month, relations) => {
        return request('get', ['budgets', month, 'incomes'], null, relations)
    },

    // Create a new budget income
    addIncome: (month, income, relations) => {
        return request('post', ['budgets', month, 'incomes'], income, relations)
    },

    // Update a budget income
    updateIncome: (month, incomeId, income, relations) => {
        return request('patch', ['budgets', month, 'incomes', incomeId], income, relations)
    },

    // Delete a budget income
    deleteIncome: (month, incomeId, relations) => {
        return request('delete', ['budgets', month, 'incomes', incomeId], {}, relations)
    }
}
