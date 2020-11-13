import { request } from '@/scripts/http/utils';

/**
 * Budget Entry Requests
 */

export default {
    // Get all entries for the given budget by month
    getEntries: (month, relations) => {
        return request('get', ['budgets', month, 'entries'], {}, relations)
    },

    // Get a budget entry
    getEntry: (month, entryId, relations) => {
        return request('get', ['budgets', month, 'entries', entryId], {}, relations)
    },

    // Create a budget entry
    addEntry: (month, entry, relations) => {
        return request('post', ['budgets', month, 'entries'], entry, relations)
    },

    // Update a budget entry
    updateEntry: (month, entryId, entry, relations) => {
        return request('patch', ['budgets', month, 'entries', entryId], entry, relations)
    },

    // Delete a budget entry
    deleteEntry: (month, entryId, relations) => {
        return request('delete', ['budgets', month, 'entries', entryId], {}, relations)
    }
}
