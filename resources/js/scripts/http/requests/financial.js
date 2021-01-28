import { request } from '@/scripts/http/utils';

/**
 * Financial Requests
 */

export default {
    // Request a link token to be used to link bank accounts
    financialLinkToken: (relations) => {
        return request('post', ['financial', 'token', 'link'], null, relations)
    },

    // Exchange the public token given by the initialization process to get an access token
    financialPublicToken: (publicToken, relations) => {
        return request('post', ['financial', 'token', 'public'], { publicToken }, relations)
    },

    // Get all financial institutions
    financialInstitutions: (relations) => {
        return request('get', ['financial', 'institutions'], null, relations)
    },

    // Get all financial institutions
    financialInstitution: (institutionId, relations) => {
        return request('get', ['financial', 'institutions', institutionId], null, relations)
    },

    // // Get all financial accounts
    // financialAccounts: (institutionId, relations) => {
    //     return request('get', ['financial', 'institutions', institutionId, 'accounts'], null, relations)
    // },
    //
    // // Get a financial account
    // financialAccount: (institutionId, accountId, relations) => {
    //     return request('get', ['financial', 'institutions', institutionId, 'accounts', accountId], null, relations)
    // },

    // Update a financial account
    financialUpdateAccount: (institutionId, accountId, account, relations) => {
        return request('patch', ['financial', 'institutions', institutionId, 'accounts', accountId], account, relations)
    },

    // get financial account transactions
    financialTransactions: (institutionId, accountId, relations) => {
        return request('get', ['financial', 'institutions', institutionId, 'accounts', accountId, 'transactions'], relations)
    },

    // get financial account transactions
    financialTransactionsStore: (institutionId, accountId, relations) => {
        return request('post', ['financial', 'institutions', institutionId, 'accounts', accountId, 'transactions'], relations)
    }
}
