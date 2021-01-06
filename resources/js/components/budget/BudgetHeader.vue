<template>

    <nav class="bg-white rounded-lg rounded-b-none shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center text-2xl font-bold text-gray-700 sm:text-2xl sm:truncate">
                        <icon name="calendar" size="6" class="text-blue-500 mr-1"></icon>
                        {{ monthName }}
                        <span class="text-sm text-blue-600 pt-2 ml-1">{{ year }}</span>
                    </div>
                </div>
                <div class="flex gap-x-2 items-center">
                    <div class="flex-shrink-0">
                        <ub-button @click="previous" icon-left="chevronDoubleLeft" variant="secondary" outline size="sm">
                            <span class="hidden sm:inline">Previous</span>
                        </ub-button>
                    </div>
                    <div class="flex-shrink-0">
                        <!-- This example requires Tailwind CSS v2.0+ -->
                        <div class="relative inline-block text-left">
                            <div>
                                <ub-button @click="showViewMenu = !showViewMenu" @blur="showViewMenu = false" variant="secondary" outline size="sm">
                                    <icon name="cog" size="5" class="-l-mr-1 ml-2"></icon>
                                    <span class="hidden sm:inline ml-2">Options</span>
                                    <icon name="chevronDown" class="-l-mr-1 ml-2"></icon>
                                </ub-button>
                            </div>
                            <transition-fade>
                                <div v-show="showViewMenu" class="origin-top-right absolute right-0 mt-2 w-56 z-50 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                    <div>
                                        <a href="#" @click="today" class="group px-2 py-1 flex items-center py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                            <icon name="calendar" class="mr-2 text-gray-500"></icon>
                                            Current Month
                                        </a>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 truncate px-2 py-1">
                                            View
                                        </p>
                                        <a href="#" @click="changeView('incomes')" :class="{ 'font-bold': viewActive('incomes') }" class="group px-2 py-1 flex items-center py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                            <icon v-if="viewActive('incomes')" name="check" class="float-left text-green-700 mr-2"></icon>
                                            <span :class="{'ml-7': !viewActive('incomes')}">View by Income</span>
                                        </a>
                                        <a href="#" @click="changeView('groups')" :class="{ 'font-bold': viewActive('groups') }" class="group px-2 py-1 flex items-center py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                            <icon v-if="viewActive('groups')" name="check" class="float-left text-green-700 mr-2"></icon>
                                            <span :class="{'ml-7': !viewActive('groups')}">View by Group</span>
                                        </a>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 truncate px-2 py-1">
                                            Manage
                                        </p>
                                        <a href="#" @click="changeView('create-income')" :class="{ 'font-bold': viewActive('create-income') }" class="group px-2 py-1 flex items-center py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                            <icon name="plus" class="float-left text-green-700 mr-2"></icon>
                                            <span>Create Income</span>
                                        </a>
                                        <a href="#" @click="changeView('create-group')" :class="{ 'font-bold': viewActive('manage-groups') }" class="group px-2 py-1 flex items-center py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                            <icon name="plus" class="float-left text-green-700 mr-2"></icon>
                                            <span>Create Group</span>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="#" @click="changeView('delete-budget')" :class="{ 'font-bold': viewActive('delete-budget') }" class="group px-2 py-1 flex items-center py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                            <icon name="trash" class="float-left text-green-700 mr-2"></icon>
                                            <span>Delete Budget</span>
                                        </a>
                                    </div>
                                </div>
                            </transition-fade>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <ub-button @click="next" icon-right="chevronDoubleRight" variant="secondary" outline size="sm">
                            <span class="hidden sm:inline">Next</span>
                        </ub-button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
    import moment from 'moment'
    import TransitionFade from '@/components/transitions/TransitionFade'

    export default {

        components: {
            TransitionFade
        },

        props: {
            view: {
                type: String,
                default: null
            },
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
                const view = this.$store.getters.settings.view
                return view.charAt(0).toUpperCase() + view.slice(1) + ' View'
            },
            monthName () {
                if (this.budgetDate) {
                    return moment(this.budgetDate).format('MMMM')
                }
                return ''
            },
            month () {
                return this.$route.params.month
            },
            year () {
                return this.$route.params.year
            },
            budgetDate () {
                if (this.year && this.month) {
                    return `${this.year}-${this.month}-01`
                }
            },
            isCurrentMonth () {
                const current = moment().format('YYYY-MM') + '-01'
                return this.budgetDate === current
            }
        },

        methods: {
            viewActive (view) {
                return this.view === view
            },
            changeView (view) {
                let reload = false
                if (view === 'incomes' || view === 'groups') {
                    this.$http.updateSettings({ view: view })
                    this.$store.dispatch('settings', { view: view })
                    reload = true
                } else if (view === 'create-income') {
                    this.$http.updateSettings({ view: 'incomes' })
                    this.$store.dispatch('settings', { view: 'incomes' })
                    reload = true
                } else if (view === 'create-group') {
                    this.$http.updateSettings({ view: 'groups' })
                    this.$store.dispatch('settings', { view: 'groups' })
                    reload = true
                }
                this.showViewMenu = false
                this.showMenu = false
                this.$emit('view-changed', view, reload)
            },
            toggleMenu () {
                this.showMenu = !this.showMenu
            },
            previous () {
                const date = moment(this.budgetDate).subtract(1, 'month')
                this.redirect(date)
            },
            today () {
                if (!this.isCurrentMonth) {
                    const date = moment()
                    this.redirect(date)
                }
            },
            next () {
                const date = moment(this.budgetDate).add(1, 'month')
                this.redirect(date)
            },

            async redirect (date) {
                const year = date.format('YYYY')
                const month = date.format('MM')
                await this.$router.push({ name: 'budget', params: { year, month }})
            }
        }

    }

</script>
