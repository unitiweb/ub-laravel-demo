import updateEntry from '@/scripts/budgetTasks/tasks/updateEntry'
import deleteEntry from '@/scripts/budgetTasks/tasks/deleteEntry'
import sortEntries from '@/scripts/budgetTasks/tasks/sortEntries'
import moveEntry from '@/scripts/budgetTasks/tasks/moveEntry'

export default {
    /**
     * Update budget entry and sort entries
     */
    updateEntry,

    /**
     * Delete budget entry
     */
    deleteEntry,

    /**
     * Sort all budget entries
     */
    sortEntries,

    /**
     * Check if an entry needs moved and if so then move it
     */
    moveEntry
}
