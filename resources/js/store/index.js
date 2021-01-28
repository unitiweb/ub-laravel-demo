import Vuex from 'vuex'
import Vue from 'vue'
import config from '@/config'
import moment from 'moment'

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
        user: {},
        settings: {}
    },
    getters: {
        loading: state => {
            return state.loading
        },
        accessToken: state => {
            return state.auth.tokens.access || { ttl: 0, token: '' }
        },
        appLoaded: state => {
            return state.auth.appLoaded
        },
        isLoggedIn: state => {
            return state.auth.isLoggedIn
        },
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
        tokensConfig: state => {
            return state.auth.tokens.config
        },
        user: state => {
            return state.user
        },
        fullName: state => {
            return state.user.firstName + ' ' + state.user.lastName
        },
        email: state => {
            return state.user.email
        },
        avatar: (state) => {
            const avatar = state.user.avatar
            if (avatar && avatar.length > 0) {
                return `${config.AVATAR_BASE_PATH}/${avatar}`
            }
            // default if since an avatar is not set
            // return '/assets/static/img/no-photo.png'
        },
        site: state => {
            return state.site
        },
        settings: state => {
            return state.settings
        },
        budget: state => {
            return state.budget
        },
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
        bankInstitutions: state => {
            return state.bankInstitutions
        },
        bankInstitution: state => {
            return state.bankInstitution
        },
        bankAccount: state => {
            return state.bankAccount
        }
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
            for (const [key, value] of Object.entries(payload)) {
                user[key] = value
            }
            commit('user', user)
        },
        site ({ commit, getters }, payload) {
            const site = getters.site
            // Update only the given values
            for (const [key, value] of Object.entries(payload)) {
                site[key] = value
            }
            commit('site', site)
        },
        async updateSettings ({ commit, getters }, payload) {
            const settings = getters.settings
            // Update only the given values
            for (const [key, value] of Object.entries(payload)) {
                settings[key] = value
            }
            await commit('settings', settings)
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
        }
    }
})
