/**
 * Load the required imports
 */
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueRouterPrefetch from 'vue-router-prefetch'
import store from '@/store'

/**
 * Load the layout components
 */
import BaseLayout from '@/components/layouts/BaseLayout';
import AuthLayout from "@/components/layouts/AuthLayout";
import SecureLayout from "@/components/layouts/SecureLayout";

/**
 * Load the view components
 */
import Budget from '@/views/dashboard/budget/Budget'
import Login from '@/views/auth/Login'
import Locked from '@/views/auth/Locked'
import Lock from '@/views/auth/Lock'
import Logout from '@/views/auth/Logout'

/**
 * Middleware
 */

import Authentication from '@/router/middleware/authentication'
import ClearLoading from '@/router/middleware/clearLoading'

/**
 * Add the plugins to the Vue instance
 */
Vue.use(VueRouter)
Vue.use(VueRouterPrefetch)

/**
 * Define the routes
 *
 * @type {{path: string, component: {}, name: string}[]}
 */
const routes = [
    {
        path: '/',
        name: 'Home',
        component: BaseLayout,
        redirect: { name: 'login' },
        children: [
            {
                path: '/auth',
                name: 'auth',
                component: AuthLayout,
                children: [
                    {
                        path: 'login',
                        name: 'login',
                        component: Login
                    }, {
                        path: 'locked',
                        name: 'locked',
                        component: Locked
                    }, {
                        path: 'lock',
                        name: 'lock',
                        component: Lock
                    }, {
                        path: 'logout',
                        name: 'logout',
                        component: Logout
                    }
                ]
            }, {
                path: 'dashboard',
                name: 'dashboard',
                component: SecureLayout,
                meta: { privateAccess: true },
                redirect: { name: 'budget' },
                children: [
                    {
                        path: 'budget/:year?/:month?',
                        name: 'budget',
                        component: Budget
                    }
                ]
            }
        ]
    }
]

/**
 * Create the vue router
 *
 * @type {VueRouter}
 */
const router = new VueRouter({
    mode: 'history',
    hash: false,
    routes,
    linkActiveClass: 'active',
    scrollBehavior: (to) => {
        if (to.hash) {
            return {selector: to.hash}
        } else {
            return { x: 0, y: 0 }
        }
    }
})

router.beforeEach(Authentication)
router.beforeEach(ClearLoading)

/**
 * Export the vue router
 */
export default router
