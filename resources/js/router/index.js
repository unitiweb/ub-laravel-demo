/**
 * Load the required imports
 */
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueRouterPrefetch from 'vue-router-prefetch'

/**
 * Load the layout components
 */
import BaseLayout from '../components/layouts/BaseLayout';
import AuthLayout from "../components/layouts/AuthLayout";
import DashboardLayout from "../components/layouts/dashboard/DashboardLayout";

/**
 * Load the view components
 */
import Budget from '../views/dashboard/budget/Budget'
import Login from "../views/auth/Login";

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
                    }
                ]
            }, {
                path: 'dashboard',
                name: 'dashboard',
                component: DashboardLayout,
                redirect: { name: 'budget' },
                children: [
                    {
                        path: 'budget',
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

/**
 * Export the vue router
 */
export default router
