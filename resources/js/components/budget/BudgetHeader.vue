<template>

    <nav class="bg-white rounded-lg rounded-b-none shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="-ml-2 mr-2 flex items-center md:hidden">
                        <!-- Mobile menu button -->
                        <button @click="toggleMenu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <!-- Icon when menu is closed. -->
                            <!--
                              Heroicon name: menu

                              Menu open: "hidden", Menu closed: "block"
                            -->
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <!-- Icon when menu is open. -->
                            <!--
                              Heroicon name: x

                              Menu open: "block", Menu closed: "hidden"
                            -->
                            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-shrink-0 flex items-center text-2xl font-bold text-gray-700 sm:text-2xl sm:truncate">
                        {{ monthName }}
                        <span class="text-sm text-gray-600 pt-2 ml-2">{{ year }}</span>
<!--                        <img class="block lg:hidden h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow">-->
<!--                        <img class="hidden lg:block h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-indigo-600-mark-gray-800-text.svg" alt="Workflow">-->
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">

                        <div class="ml-3 relative inline-flex items-center">
                            <div>
<!--                                <a href="#" @click.prevent="showViewMenu = !showViewMenu" @blur="showViewMenu = false" class=" px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium text-gray-900">-->
<!--                                    {{ budgetViewName }}-->
<!--                                </a>-->
                                <a href="#" @click.prevent="showViewMenu = !showViewMenu" @blur="showViewMenu = false" class=" px-1 pt-1 text-sm font-medium text-gray-900">
                                    {{ budgetViewName }}
                                </a>
                                <transition enter-active-class="transition ease-out duration-200"
                                            enter-class="transform opacity-0 scale-95"
                                            enter-to-class="transform opacity-100 scale-100"
                                            leave-active-class="transition ease-in duration-75"
                                            leave-class="transform opacity-100 scale-100"
                                            leave-to-class="transform opacity-0 scale-95">
                                    <div v-show="showViewMenu" class="absolute mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                        <a href="#" @click.prevent="changeView('incomes')" :class="{ 'font-bold': viewActive('incomes') }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <icon v-if="viewActive('incomes')" name="check" class="float-left text-green-700 mr-2 h-5 w-5"></icon>
                                            Incomes View
                                        </a>
                                        <a href="#" @click.prevent="changeView('groups')" :class="{ 'font-bold': viewActive('groups') }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            <icon v-if="viewActive('groups')" name="check" class="float-left text-green-700 mr-2 h-5 w-5"></icon>
                                            Groups View
                                        </a>
                                    </div>
                                </transition>
                            </div>
                        </div>

                        <div class="ml-3 relative inline-flex items-center">
                            <div>
                                <a href="#" @click.prevent="showManageMenu = !showManageMenu" @blur="showManageMenu = false" class=" px-1 pt-1 text-sm font-medium text-gray-900">
                                    Manage
                                </a>
                                <transition enter-active-class="transition ease-out duration-200"
                                            enter-class="transform opacity-0 scale-95"
                                            enter-to-class="transform opacity-100 scale-100"
                                            leave-active-class="transition ease-in duration-75"
                                            leave-class="transform opacity-100 scale-100"
                                            leave-to-class="transform opacity-0 scale-95">
                                    <div v-show="showManageMenu" class="absolute mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                        <router-link :to="{ name: 'budget' }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            Incomes
                                        </router-link>
                                        <router-link :to="{ name: 'budget' }" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            Groups
                                        </router-link>
                                    </div>
                                </transition>
                            </div>
                        </div>

<!--                        <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">-->
<!--                            Group View-->
<!--                        </a>-->
<!--                        <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">-->
<!--                            Projects-->
<!--                        </a>-->
<!--                        <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">-->
<!--                            Calendar-->
<!--                        </a>-->
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <button type="button" class="relative inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <!-- Heroicon name: plus -->
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span>New Job</span>
                        </button>
                    </div>
                    <div class="hidden md:ml-4 md:flex-shrink-0 md:flex md:items-center">
                        <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">View notifications</span>
                            <!-- Heroicon name: bell -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>

                        <!-- Profile dropdown -->
<!--                        <div class="ml-3 relative">-->
<!--                            <div>-->
<!--                                <button class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu" aria-haspopup="true">-->
<!--                                    <span class="sr-only">Open user menu</span>-->
<!--                                    <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">-->
<!--                                </button>-->
<!--                            </div>-->
<!--                            &lt;!&ndash;-->
<!--                              Profile dropdown panel, show/hide based on dropdown state.-->

<!--                              Entering: "transition ease-out duration-200"-->
<!--                                From: "transform opacity-0 scale-95"-->
<!--                                To: "transform opacity-100 scale-100"-->
<!--                              Leaving: "transition ease-in duration-75"-->
<!--                                From: "transform opacity-100 scale-100"-->
<!--                                To: "transform opacity-0 scale-95"-->
<!--                            &ndash;&gt;-->
<!--                            <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">-->
<!--                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</a>-->
<!--                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>-->
<!--                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Sign out</a>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>


        <!--
    Mobile menu, toggle classes based on menu state.

    Menu open: "block", Menu closed: "hidden"
  -->
        <div v-show="showMenu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium">View</a>
                <a href="#"
                   @click.prevent="changeView('incomes')"
                   class="block ml-4 pl-3 pr-4 py-2 text-base font-medium sm:pl-5 sm:pr-6"
                   :class="{'border-l-4 border-indigo-500 text-indigo-700 bg-indigo-50': viewActive('incomes')}">
                    Incomes View
                </a>
                <a href="#"
                   @click.prevent="changeView('groups')"
                   class="block ml-4 pl-3 pr-4 py-2 text-base font-medium sm:pl-5 sm:pr-6"
                   :class="{'border-l-4 border-indigo-500 text-indigo-700 bg-indigo-50': viewActive('groups')}">
                    Groups View
                </a>
            </div>
            <div class="pt-2 pb-3 space-y-1">
                <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium">Manage</a>
                <router-link :to="{ name: 'budget' }"
                             @click.prevent="changeView('incomes')"
                             class="block ml-4 pl-3 pr-4 py-2 text-base font-medium sm:pl-5 sm:pr-6"
                             :class="{'border-l-4 border-indigo-500 text-indigo-700 bg-indigo-50': viewActive('incomes')}">
                    Manage Incomes
                </router-link>
                <router-link :to="{ name: 'budget' }"
                   @click.prevent="changeView('groups')"
                   class="block ml-4 pl-3 pr-4 py-2 text-base font-medium sm:pl-5 sm:pr-6"
                   :class="{'border-l-4 border-indigo-500 text-indigo-700 bg-indigo-50': viewActive('groups')}">
                    Manage Groups
                </router-link>
            </div>
<!--            <div class="pt-4 pb-3 border-t border-gray-200">-->
<!--                <div class="flex items-center px-4 sm:px-6">-->
<!--                    <div class="flex-shrink-0">-->
<!--                        <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">-->
<!--                    </div>-->
<!--                    <div class="ml-3">-->
<!--                        <div class="text-base font-medium text-gray-800">Tom Cook</div>-->
<!--                        <div class="text-sm font-medium text-gray-500">tom@example.com</div>-->
<!--                    </div>-->
<!--                    <button class="ml-auto flex-shrink-0 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">-->
<!--                        <span class="sr-only">View notifications</span>-->
<!--                        &lt;!&ndash; Heroicon name: bell &ndash;&gt;-->
<!--                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">-->
<!--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />-->
<!--                        </svg>-->
<!--                    </button>-->
<!--                </div>-->
<!--                <div class="mt-3 space-y-1">-->
<!--                    <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 sm:px-6">Your Profile</a>-->
<!--                    <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 sm:px-6">Settings</a>-->
<!--                    <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 sm:px-6">Sign out</a>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </nav>





<!--    <div class="md:flex lg:items-center lg:justify-between">-->
<!--        <div class="flex-1 min-w-0">-->
<!--            <h2 class="text-2xl font-bold leading-7 text-gray-700 sm:text-3xl sm:truncate">-->
<!--                {{ monthName }}-->
<!--                <span class="text-sm text-gray-600">{{ year }}</span>-->
<!--            </h2>-->
<!--            <div class="hidden sm:inline mt-1 pt-2 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-2">-->
<!--                <ub-badge variant="success" outline small>-->
<!--                    Income: {{ income | currency }}-->
<!--                </ub-badge>-->
<!--                <ub-badge variant="danger" outline small>-->
<!--                    Expenses: {{ expenses | currency }}-->
<!--                </ub-badge>-->
<!--                <ub-badge variant="success" outline small>-->
<!--                    Left Over: {{ leftOver | currency }}-->
<!--                </ub-badge>-->
<!--            </div>-->
<!--            <div class="inline sm:hidden mt-1 pt-2 flex flex-row flex-wrap text-center mt-0 space-x-2">-->
<!--                <ub-badge variant="success" outline small class="flex-1 align-center">-->
<!--                    Income: {{ income | currency }}-->
<!--                </ub-badge>-->
<!--                <ub-badge variant="danger" outline small class="flex-1">-->
<!--                    Expenses: {{ expenses | currency }}-->
<!--                </ub-badge>-->
<!--                <ub-badge variant="success" outline small class="flex-1">-->
<!--                    Left Over: {{ leftOver | currency }}-->
<!--                </ub-badge>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="mt-5 flex lg:mt-0 lg:ml-4">-->
<!--            <ub-button @click="previous" outline block icon="chevronDoubleLeft" class="mr-1"></ub-button>-->
<!--            <ub-button @click="today" outline block icon="calendar"></ub-button>-->
<!--            <ub-button @click="next" outline block icon="chevronDoubleRight" class="ml-1"></ub-button>-->
<!--        </div>-->
<!--    </div>-->
</template>

<script>
    import moment from 'moment'

    export default {

        props: {
            income: {
                type: Number,
                default: 0
            },
            expenses: {
                type: Number,
                default: 0
            },
            leftOver: {
                type: Number,
                default: 0
            }
        },

        data () {
            return {
                showMenu: false,
                showViewMenu: false,
                showManageMenu: false,
            }
        },

        computed: {
            budgetViewName () {
                const view = this.$store.getters.lastView
                return view.charAt(0).toUpperCase() + view.slice(1) + ' View'
            },
            monthName () {
                return moment(this.budgetDate).format('MMMM')
            },
            month () {
                return this.$route.params.month
            },
            year () {
                return this.$route.params.year
            },
            budgetDate () {
                return `${this.year}-${this.month}-01`
            }
        },

        methods: {
            viewActive (view) {
                return this.$store.getters.lastView === view
            },
            async changeView (view) {
                try {
                    await this.$http.updateSettings({ lastView: view })
                    await this.$store.dispatch('lastView', data.lastView)
                    this.showViewMenu = false
                    this.showMenu = false
                } catch ({ error }) {
                    console.log('error', error)
                    this.showViewMenu = false
                    this.showMenu = false
                }
            },
            toggleMenu () {
                this.showMenu = !this.showMenu
            },
            previous () {
                const date = moment(this.budgetDate).subtract(1, 'month')
                this.redirect(date)
            },
            today () {
                const date = moment()
                this.redirect(date)
            },
            next () {
                const date = moment(this.budgetDate).add(1, 'month')
                this.redirect(date)
            },

            redirect (date) {
                const year = date.format('YYYY')
                const month = date.format('MM')
                this.$router.push({ name: 'budget', params: { year, month }})
            }
        }

    }

</script>
