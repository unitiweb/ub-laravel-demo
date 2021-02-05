/**
 * Remove the given entry from the budget
 *
 * @param budget
 * @param entry
 *
 * @returns {budget}
 */
const deleteEntry = async (budget, entry) => {
    // If entry is in the incomes view then remove
    if (budget.incomes && entry.income) {
        for (let i = 0; i < budget.incomes.length; i++) {
            for (let ii = 0; ii < budget.incomes[i].entries.length; ii++) {
                if (budget.incomes[i].entries[ii].id === entry.id) {
                    budget.incomes[i].entries.splice(ii, ii + 1)
                    i = budget.incomes.length
                    break;
                }
            }
        }
    }

    // if entry is an unassigned income entry
    if (budget.unassignedIncomeEntries && !entry.income) {
        for (let ii = 0; ii < budget.unassignedIncomeEntries.length; ii++) {
            if (budget.unassignedIncomeEntries[ii].id === entry.id) {
                budget.unassignedIncomeEntries.splice(ii, ii + 1)
                break;
            }
        }
    }

    // If entry is in the groups view then remove
    if (budget.groups && entry.group) {
        for (let i = 0; i < budget.groups.length; i++) {
            for (let ii = 0; ii < budget.groups[i].entries.length; ii++) {
                if (budget.groups[i].entries[ii].id === entry.id) {
                    budget.groups[i].entries.splice(ii, ii + 1)
                    i = budget.groups.length
                    break;
                }
            }
        }
    }

    // If the entry is an unassigned group entry
    if (budget.unassignedGroupEntries && !entry.group) {
        for (let ii = 0; ii < budget.unassignedGroupEntries.length; ii++) {
            if (budget.unassignedGroupEntries[ii].id === entry.id) {
                budget.unassignedGroupEntries.splice(ii, ii + 1)
                break;
            }
        }
    }

    return budget
}

export default deleteEntry
