/**
 * Load the required imports
 */
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueRouterPrefetch from 'vue-router-prefetch'
import store from '@/store'

/**
 * Other needed imports
 */
import moment from 'moment'

/**
 * Load the layout components
 */
import BaseLayout from '@/components/layouts/BaseLayout';
import AuthLayout from "@/components/layouts/AuthLayout";
import DashboardLayout from "@/components/layouts/DashboardLayout";

/**
 * Load the auth components
 */
import Login from '@/views/auth/Login'
import Locked from '@/views/auth/Locked'
import Lock from '@/views/auth/Lock'
import Logout from '@/views/auth/Logout'
import Register from '@/views/register/Register'
import VerifyEmail from '@/views/auth/VerifyEmail'
import ForgotPassword from '@/views/auth/ForgotPassword'
import ForgotPasswordValidate from '@/views/auth/ForgotPasswordValidate'

/**
 * Load the budget components
 */
import Budget from '@/views/dashboard/budget/Budget'

/**
 * Load the banks components
 */
import Banks from '@/views/dashboard/banks/Banks'

/**
 * Load the profile components
 */
import Profile from '@/views/dashboard/profile/Profile'

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
                    }, {
                        path: 'register',
                        name: 'register',
                        component: Register
                    }, {
                        path: 'verify-email',
                        name: 'verify-email',
                        component: VerifyEmail
                    }, {
                        path: 'forgot-password',
                        name: 'forgot-password',
                        component: ForgotPassword
                    }, {
                        path: 'forgot-password-validate',
                        name: 'forgot-password-validate',
                        component: ForgotPasswordValidate
                    }
                ]
            }, {
                path: 'dashboard',
                name: 'dashboard',
                component: DashboardLayout,
                meta: { privateAccess: true },
                redirect: { name: 'budget' },
                children: [
                    {
                        path: 'budget/:year?/:month?',
                        name: 'budget',
                        component: Budget
                    }, {
                        path: 'profile',
                        name: 'profile',
                        component: Profile
                    }, {
                        path: 'banks',
                        name: 'banks',
                        component: Banks
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
