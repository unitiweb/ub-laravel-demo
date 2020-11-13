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

    // Get all financial accounts
    financialAccounts: (relations) => {
        return request('get', ['financial', 'accounts'], null, relations)
    },

    // Get a financial account
    financialAccount: (accountId, relations) => {
        return request('get', ['financial', 'accounts', accountId], null, relations)
    },

    // Update a financial account
    financialUpdateAccount: (accountId, account, relations) => {
        return request('patch', ['financial', 'accounts', accountId], account, relations)
    }
}
