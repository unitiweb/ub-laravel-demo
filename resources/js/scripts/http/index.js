import authRequests from '@/scripts/http/requests/auth'
import profileRequests from '@/scripts/http/requests/profile'
import budgetRequests from '@/scripts/http/requests/budget'
import entryRequests from '@/scripts/http/requests/entries'
import budgetGroupRequests from '@/scripts/http/requests/budgetGroups'
import groupRequests from '@/scripts/http/requests/groups'
import incomeRequests from '@/scripts/http/requests/incomes'
import financialRequests from '@/scripts/http/requests/financial'

/**
 * Gather all the requests files
 */

const context = {
    ...authRequests,
    ...profileRequests,
    ...budgetRequests,
    ...entryRequests,
    ...budgetGroupRequests,
    ...groupRequests,
    ...incomeRequests,
    ...financialRequests
}

export const $http = context

export default {
    install(Vue, options) {
        Vue.prototype.$http = context
    }
}
