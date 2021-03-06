import Vuex from 'vuex'
import Vue from 'vue'
import config from '@/config'
import moment from 'moment'
import { updateObject } from '@/scripts/helpers/utils'
import budgetTasks from '@/scripts/budgetTasks'
import { $http } from '@/scripts/http'

// Add Vuex to the Vue instance
Vue.use(Vuex)

// Define the store
export default new Vuex.Store({
    state: {
        auth: {
            appLoaded: false,
            isLoggedIn: false,
            isLocked: false,
            tokens: {
                config: {
                    // The initial interval for checking ttl
                    // Once countdown is started it will be 1
                    timeout: 5,
                    // The ttl value where the countdown will start
                    notice: 60,
                },
                // The access jwt token details
                access: {
                    ttl: 0,
                    token: ''
                },
                // The refresh jwt token details
                refresh: {
                    ttl: 0,
                    token: ''
                }
            }
        },
        loading: false,
        site: {},
        budgetDate: null,
        budget: null,
        bankInstitutions: [],
        bankInstitution: null,
        bankAccount: null,
        bankTransactions: null,
        user: {},
        settings: {},
        incomeStats: {}
    },
    getters: {
        loading: state => state.loading,
        accessToken: state => state.auth.tokens.access || { ttl: 0, token: '' },
        appLoaded: state => state.auth.appLoaded,
        isLoggedIn: state => state.auth.isLoggedIn,
        isLocked: state => {
            const user = localStorage.getItem(config.AUTH_USER_LOCKED)
            state.auth.isLocked = JSON.parse(user)
            return state.auth.isLocked
        },
        refreshToken: state => {
            const token = localStorage.getItem(config.LOCAL_REFRESH_TOKEN_KEY)
            state.auth.tokens.refresh.token = token
            return token
        },
        tokensConfig: state => state.auth.tokens.config,
        user: state => state.user,
        fullName: state => state.user.firstName + ' ' + state.user.lastName,
        email: state => state.user.email,
        avatar: (state) => {
            const avatar = state.user.avatar
            if (avatar && avatar.length > 0) {
                return `${config.AVATAR_BASE_PATH}/${avatar}`
            }
            // default if since an avatar is not set
            // return '/assets/static/img/no-photo.png'
        },
        site: state => state.site,
        settings: state => state.settings,
        budget: state => state.budget,
        budgetDate: state => {
            if (state.budget) {
                return moment(state.budget.month, "YYYY-M-DD")
            } else {
                return moment()
            }
        },
        budgetDateFormatted: state => {
            if (state.budget) {
                return moment(state.budget.month, "YYYY-M-DD").format('YYYY-MM-DD')
            } else {
                return moment().format('YYYY-MM-DD')
            }
        },
        bankInstitutions: state => state.bankInstitutions,
        bankInstitution: state => state.bankInstitution,
        bankAccount: state => state.bankAccount,
        bankTransactions: state => state.bankTransactions,
        incomeStats: state => state.incomeStats
    },
    mutations: {
        loading (state, loading) {
            state.loading = loading
        },
        appLoaded (state, loaded) {
            state.auth.appLoaded = loaded
        },
        loggedIn (state, loggedIn) {
            state.auth.isLoggedIn = loggedIn
        },
        locked (state, user) {
            if (user) {
                localStorage.setItem(config.AUTH_USER_LOCKED, JSON.stringify(user))
            } else {
                localStorage.removeItem(config.AUTH_USER_LOCKED)
            }
        },
        user (state, user) {
            state.user = user
        },
        site (state, site) {
            state.site = site
        },
        avatar (state, avatar) {
            state.user.avatar = avatar
        },
        async settings (state, settings) {
            state.settings = settings
        },
        tokens (state, tokens) {
            if (!tokens) {
                state.auth.tokens.access.token = ''
                state.auth.tokens.access.ttl = 0
                state.auth.tokens.refresh.token = ''
                state.auth.tokens.refresh.ttl = 0
                localStorage.removeItem(config.LOCAL_REFRESH_TOKEN_KEY)
            } else {
                state.auth.tokens.access = tokens.access
                state.auth.tokens.refresh = tokens.refresh
                localStorage.setItem(config.LOCAL_REFRESH_TOKEN_KEY, tokens.refresh.token)
            }
        },
        budget (state, budget) {
            state.budget = budget
        },
        budgetDate (state, date) {
            state.budgetDate = date
        },
        bankInstitutions (state, institutions) {
            state.bankInstitutions = institutions
        },
        bankInstitution (state, institution) {
            state.bankInstitution = institution
        },
        bankAccount (state, account) {
            state.bankAccount = account
        },
        bankTransactions (state, transactions) {
            state.bankTransactions = transactions
        },
        incomeStats (state, stats) {
            state.incomeStats = stats
        }
    },
    actions: {
        setLoading ({ commit }, loading) {
            commit('loading', loading)
        },
        login ({ commit, getters }, payload) {
            commit('site', payload.data.site)
            commit('settings', payload.data.settings)
            delete payload.data.site
            delete payload.data.settings
            commit('user', payload.data)
            commit('tokens', payload.tokens)
            commit('appLoaded', true)
            commit('loggedIn', true)
            commit('locked', null)
        },
        lock ({ commit, getters }) {
            commit('locked', getters.user)
            commit('loggedIn', false)
            commit('appLoaded', false)
            commit('site', {})
            commit('settings', {})
            commit('user', {})
            commit('tokens', null)
        },
        logout ({ commit }) {
            commit('locked', null)
            commit('loggedIn', false)
            commit('appLoaded', false)
            commit('site', {})
            commit('settings', {})
            commit('user', {})
            commit('tokens', null)
        },
        user ({ commit, getters }, payload) {
            const user = getters.user
            // Update only the given values
            updateObject(user, payload)
            commit('user', user)
        },
        site ({ commit, getters }, payload) {
            const site = getters.site
            // Update only the given values
            updateObject(site, payload)
            commit('site', site)
        },
        async updateSettings ({ commit, getters }, payload) {
            const settings = getters.settings
            // Update only the given values
            updateObject(settings, payload)
            await commit('settings', settings)
        },
        /**
         * Update the budget entry with the given entry
         * Check both the incomes and groups arrays
         */
        async updateBudgetEntry ({ commit, getters }, entry) {
            let budget = getters.budget
            budget = await budgetTasks.updateEntry(budget, entry)
            budget = await budgetTasks.moveEntry(budget)
            budget = await budgetTasks.sortEntries(budget)
            commit('budget', budget)
        },
        async addBudgetEntry ({ commit, getters }, entry) {
            const budget = getters.budget
            // Create sort function
            const sortEntries = (data) => {
                data.sort(function(a, b) {
                    if (a.dueDay === b.dueDay) {
                        return a.name.localeCompare(b.name)
                    }
                    return a.dueDay - b.dueDay;
                });
            }
            // Handle the entry incomes
            if (entry.income) {
                for (let i = 0; budget.incomes.length; i++) {
                    if (budget.incomes[i].id === entry.income.id) {
                        budget.incomes[i].entries.push(entry)
                        sortEntries(budget.incomes[i].entries)
                        break;
                    }
                }
            } else if (budget.unassignedIncomeEntries) {
                budget.unassignedIncomeEntries.push(entry)
                sortEntries(budget.unassignedIncomeEntries)
            }
            if (entry.group) {
                for (let i = 0; budget.groups.length; i++) {
                    if (budget.groups[i].id === entry.group.id) {
                        budget.groups[i].entries.push(entry)
                        sortEntries(budget.groups[i].entries)
                        break;
                    }
                }
            } else if (budget.unassignedGroupEntries) {
                budget.unassignedGroupEntries.push(entry)
                sortEntries(budget.unassignedGroupEntries)
            }
            commit('budget', budget)
        },
        async removeBudgetEntry ({ commit, getters }, entry) {
            const budget = await budgetTasks.deleteEntry(getters.budget, entry)
            commit('budget', budget)
        },
        setBudget ({ commit }, payload) {
            commit('budget', payload)
        },
        appLoaded ({ commit }, payload) {
            commit('appLoaded', payload)
        },
        budgetDate ({ commit }, date) {
            commit('budgetDate', date)
        },
        setBankInstitutions ({ commit }, institutions) {
            commit('bankInstitutions', institutions)
        },
        setBankInstitution ({ commit }, payload) {
            commit('bankInstitution', payload)
        },
        setBankAccount ({ commit }, payload) {
            commit('bankAccount', payload)
        },
        setBankTransactions ({ commit }, payload) {
            commit('bankTransactions', payload)
        },
        updateBankTransaction ({ commit, getters }, transaction) {
            const transactions = getters.bankTransactions
            for (let i = 0; i < transactions.length; i++) {
                if (transactions[i].id === transaction.id) {
                    for (const [key, value] of Object.entries(transaction)) {
                        transactions[i][key] = value
                    }
                    break;
                }
            }
            commit('bankTransactions', transactions)
        },
        removeEntryFromTransaction ({ commit, getters }, entry) {
            const transactions = getters.bankTransactions
            for (let i = 0; i < transactions.length; i++) {
                console.log('transactions.entries', transactions.entries)
                for (let ii = 0; ii < transactions[i].entries.length; ii++) {
                    if (transactions[i].entries[ii].id === entry.id) {
                        transactions[i].entries = []
                        break
                    }
                }
            }
            commit('bankTransactions', transactions)
        },
        updateBudgetIncome ({ commit, getters }, income) {
            // const budget = getters.budget
            // if (budget.incomes) {
            //     for (let i = 0; i < budget.incomes.length; i++) {
            //         if (budget.incomes[i].id === income.id) {
            //             for (const [key, value] of Object.entries(income)) {
            //                 budget.incomes[i][key] = value
            //             }
            //             break;
            //         }
            //     }
            //     commit('budget', budget)
            // }
        },
        async refreshIncomeStats ({ commit, getters }) {
            const budget = getters.budget
            const { data: { incomes } } = await $http.budgetStats(budget.month, { incomes: true })
            commit('incomeStats', incomes)
        }
    }
})
