
/**
 * Update the given entry from the budget
 *
 * @param budget
 * @param entry
 *
 * @returns {budget}
 */
const updateEntry = async (budget, entry) => {
    // Update income entry
    if (budget.incomes) {
        for (let i = 0; i < budget.incomes.length; i++) {
            for (let ii = 0; ii < budget.incomes[i].entries.length; ii++) {
                if (budget.incomes[i].entries[ii].id === entry.id) {
                    for (const [key, value] of Object.entries(entry)) {
                        budget.incomes[i].entries[ii][key] = value
                    }
                }
            }
        }
    }

    // Update groups entry
    if (budget.groups) {
        for (let i = 0; i < budget.groups.length; i++) {
            for (let ii = 0; ii < budget.groups[i].entries.length; ii++) {
                if (budget.groups[i].entries[ii].id === entry.id) {
                    for (const [key, value] of Object.entries(entry)) {
                        budget.groups[i].entries[ii][key] = value
                    }
                }
            }
        }
    }

    // Update unassigned incomes
    if (budget.unassignedIncomeEntries) {
        for (let i = 0; i < budget.unassignedIncomeEntries.length; i++) {
            if (budget.unassignedIncomeEntries[i].id === entry.id) {
                for (const [key, value] of Object.entries(entry)) {
                    budget.unassignedIncomeEntries[i][key] = value
                }
            }
        }
    }

    // Update unassigned groups
    if (budget.unassignedGroupEntries) {
        for (let i = 0; i < budget.unassignedGroupEntries.length; i++) {
            if (budget.unassignedGroupEntries[i].id === entry.id) {
                for (const [key, value] of Object.entries(entry)) {
                    budget.unassignedGroupEntries[i][key] = value
                }
            }
        }
    }

    return budget
}

export default updateEntry
