const getIndex = (data, id) => {
    for (let i = 0; i < data.length; i++) {
        if (data[i].id === id) {
            return i
        }
    }
    return null
}

/**
 * Check if an entry needs moved.
 * If so then move it
 *
 * @param budget
 *
 * @returns {budget}
 */
const moveEntries = async (budget) => {
    // Check incomes
    if (budget.incomes) {
        for (let i = 0; i < budget.incomes.length; i++) {
            for (let ii = 0; ii < budget.incomes[i].entries.length; ii++) {
                if (budget.incomes[i].entries[ii].budgetIncomeId !== budget.incomes[i].id) {
                    if (budget.incomes[i].entries[ii].budgetIncomeId === null) {
                        budget.unassignedIncomeEntries.push(budget.incomes[i].entries[ii])
                    } else {
                        const index = getIndex(budget.incomes, budget.incomes[i].entries[ii].budgetIncomeId)
                        budget.incomes[index].entries.push(budget.incomes[i].entries[ii])
                    }
                    budget.incomes[i].entries.splice(ii, 1)
                }
            }
        }
    }

    // Check groups
    if (budget.groups) {
        for (let i = 0; i < budget.groups.length; i++) {
            for (let ii = 0; ii < budget.groups[i].entries.length; ii++) {
                if (budget.groups[i].entries[ii].budgetGroupId !== budget.groups[i].id) {
                    if (budget.groups[i].entries[ii].budgetGroupId === null) {
                        budget.unassignedGroupEntries.push(budget.groups[i].entries[ii])
                    } else {
                        const index = getIndex(budget.groups, budget.groups[i].entries[ii].budgetGroupId)
                        budget.groups[index].entries.push(budget.groups[i].entries[ii])
                    }
                    budget.groups[i].entries.splice(ii, 1)
                }
            }
        }
    }

    // Check unassigned incomes entries
    if (budget.unassignedIncomeEntries) {
        for (let i = 0; i < budget.unassignedIncomeEntries.length; i++) {
            console.log('look here', budget.unassignedIncomeEntries[i].budgetIncomeId)
            if (budget.unassignedIncomeEntries[i].budgetIncomeId !== null) {
                const index = getIndex(budget.incomes, budget.unassignedIncomeEntries[i].budgetIncomeId)
                budget.incomes[index].entries.push(budget.unassignedIncomeEntries[i])
                budget.unassignedIncomeEntries.splice(i, 1)
            }
        }
    }

    // Check unassigned groups entries
    if (budget.unassignedGroupEntries) {
        for (let i = 0; i < budget.unassignedGroupEntries.length; i++) {
            if (budget.unassignedGroupEntries[i].budgetGroupId !== null) {
                const index = getIndex(budget.groups, budget.unassignedGroupEntries[i].budgetGroupId)
                budget.groups[index].entries.push(budget.unassignedGroupEntries[i])
                budget.unassignedGroupEntries.splice(i, 1)
            }
        }
    }

    return budget
}

export default moveEntries
