import Vuex from 'vuex'
import Vue from 'vue'

import config from '@/config'
import router from '@/router'

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
                    notice: 25,
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
        settings: {},
        user: {},
        page: {
            title: ''
        }
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
        isLocked: (state) => {
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
        page: state => {
            return state.page
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
        locked (state, isLocked) {
            state.auth.isLocked = isLocked
            if (isLocked !== true) {
                localStorage.removeItem(config.AUTH_USER_LOCKED)
            }
        },
        user (state, user) {
            state.user = user
        },
        site (state, site) {
            state.site = site
        },
        settings (state, settings) {
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
        page (state, payload) {
            state.page.title = payload.title
        }
    },
    actions: {
        login ({ commit, getters }, payload) {
            commit('site', payload.site)
            commit('settings', payload.settings)
            commit('user', payload.user)
            commit('tokens', payload.tokens)
            commit('appLoaded', true)
        },
        lock ({ commit }, redirect) {
            commit('locked', true)
            if (redirect === true) {
                router.push({ name: 'lock' }).catch(() => {})
            }
        },
        logout ({ commit }) {
            commit('loggedIn', false)
            commit('locked', false)
            commit('appLoaded', false)
            commit('site', {})
            commit('settings', {})
            commit('user', {})
            commit('tokens', null)
        },
        appLoaded ({ commit }, payload) {
            commit('appLoaded', payload)
        }
    }
})
