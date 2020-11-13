import store from '@/store'

/**
 * This middleware checks that the user is authenticated.
 * If the user is not authenticated we will redirect to the login page
 *
 * @param to The to route
 * @param from The from route
 * @param next The callback to move on
 */
export default async (to, from, next) => {
    // Turn off the loading on every page load
    store.commit('loading', false)
    next()
}
