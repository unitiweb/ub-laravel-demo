import store from '@/store'

/**
 * Remove the given entry from the budget
 *
 * @param entry
 * @returns {budget}
 */
export const budgetRemoveEntry = async (entry) => {
    const budget = store.getters.budget

    // If entry is in the incomes view then remove
    if (budget.incomes) {
        for (let i = 0; i < budget.incomes.length; i++) {
            for (let ii = 0; ii < budget.incomes[i].entries.length; ii++) {
                if (budget.incomes[i].entries[ii].id === entry.id) {
                    budget.incomes[i].entries.splice(ii, ii + 1)
                    i = budget.incomes.length
                    break;
                }
            }
        }
    } else if (budget.unassignedIncomeEntries) {
        for (let ii = 0; ii < budget.unassignedIncomeEntries.length; ii++) {
            if (budget.unassignedIncomeEntries[ii].id === entry.id) {
                budget.unassignedIncomeEntries.splice(ii, ii + 1)
                break;
            }
        }
    }

    // If entry is in the groups view then remove
    if (budget.groups) {
        for (let i = 0; i < budget.groups.length; i++) {
            for (let ii = 0; ii < budget.groups[i].entries.length; ii++) {
                if (budget.groups[i].entries[ii].id === entry.id) {
                    budget.groups[i].entries.splice(ii, ii + 1)
                    i = budget.groups.length
                    break;
                }
            }
        }
    } else if (budget.unassignedGroupEntries) {
        for (let ii = 0; ii < budget.unassignedGroupEntries.length; ii++) {
            if (budget.unassignedGroupEntries[ii].id === entry.id) {
                budget.unassignedGroupEntries.splice(ii, ii + 1)
                break;
            }
        }
    }

    console.log('budget', budget)

    return budget
}
