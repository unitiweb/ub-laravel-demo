/**
 * Sort income entries
 *
 * @param budget
 *
 * @returns {budget}
 */
const sortEntries = async (budget) => {
    // Sort the income entries
    if (budget.incomes) {
        for (let i = 0; i < budget.incomes.length; i++) {
            budget.incomes[i].entries.sort(function(a, b) {
                if (a.dueDay === b.dueDay) {
                    return a.name.localeCompare(b.name)
                }
                return a.dueDay - b.dueDay;
            });
        }
    }

    // Sort the unassigned income entries
    if (budget.unassignedIncomeEntries) {
        budget.unassignedIncomeEntries.sort(function(a, b) {
            if (a.dueDay === b.dueDay) {
                return a.name.localeCompare(b.name)
            }
            return a.dueDay - b.dueDay;
        });
    }

    // Sort the groups entries
    if (budget.groups) {
        for (let i = 0; i < budget.groups.length; i++) {
            budget.groups[i].entries.sort(function(a, b) {
                return a.order - b.order;
            });
        }
    }

    // Sort the unassigned group entries
    if (budget.unassignedGroupEntries) {
        budget.unassignedGroupEntries.sort(function(a, b) {
            return a.order - b.order;
        });
    }

    return budget
}

export default sortEntries
