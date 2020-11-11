import store from '@/store'
import { $http } from '@/scripts/http'

/**
 * This middleware checks that the user is authenticated.
 * If the user is not authenticated we will redirect to the login page
 *
 * @param to The to route
 * @param from The from route
 * @param next The callback to move on
 */
export default async (to, from, next) => {
    if (to.matched.some(record => record.meta.privateAccess)) {
        // If appLoaded is false then we need to refresh the login
        if (store.getters.appLoaded === false) {
            try {
                const token = store.getters.refreshToken
                const { data, site, settings, tokens } = await $http.refresh(token)
                store.dispatch('login', { user: data, site, settings, tokens })
            } catch (error) {
                store.dispatch('logout')
                next({ name: 'logout' })
            }
        }

        // If the user is not logged in then logout which will
        // also redirect to the login page
        // if (store.getters.isLoggedIn !== true) {
        //     await store.dispatch('logout', true)
        //     next({ name: 'login' })
        // }
    }
    next()
}
