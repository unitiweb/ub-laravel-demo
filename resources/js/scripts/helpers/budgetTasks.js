import store from '@/store'

/**
 * Remove the given entry from the budget
 *
 * @param entry
 *
 * @returns string|null
 */
export const budgetRemoveEntry = async (entry) => {
    const budget = store.getters.budget

    if (!budget) return null

    // If we are in incomes view then remove the entry from there
    if (budget.incomes) {
        for (const [k, v] of Object.entries(budget.incomes)) {
            for (const [key, value] of Object.entries(budget.incomes[k].entries)) {
                if (value.id === entry.id) {
                    delete budget.incomes[k].entries[key]
                }
            }
        }
    }

    // If we are in groups view then remove the entry from there
    if (budget.groups) {
        for (const [k, v] of Object.entries(budget.groups)) {
            for (const [key, value] of Object.entries(budget.groups[k].entries)) {
                if (value.id === entry.id) {
                    delete budget.groups[k].entries[key]
                }
            }
        }
    }
}
