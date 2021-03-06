<template>
    <section>
        <budget-header class="mb-4" :has-budget="!!budget" :view="currentState.left" @view-changed="viewChanged"></budget-header>
        <div class="px-4 pb-4">
            <div v-if="budgetLoaded === false">
                <budget-create :month="budgetDate" @created="budgetCreated"></budget-create>
            </div>
            <budget-divided v-if="budgetLoaded === true">
                <template v-slot:left>
                    <div :class="leftVisibility" class="h-full">
                        <div v-if="['incomes', 'groups'].includes(currentState.left)" class="h-full">
                            <budget-right-header>
                                <template v-slot:left>
                                    <ub-button @click="viewIncomes" size="sm" icon="currencyDollar" :variant="currentState.left === 'incomes' ? 'primary' : 'secondary'" outline></ub-button>
                                    <ub-button @click="viewGroups" size="sm" icon="viewBoard" :variant="currentState.left === 'groups' ? 'success' : 'secondary'" outline></ub-button>
                                </template>
                                <template v-slot:right>
                                    <ub-button @click="viewTransactions" size="sm" icon="creditCard" :variant="currentState.left === 'transactions' ? 'primary' : 'secondary'" outline></ub-button>
                                </template>
                            </budget-right-header>
                            <div>
                                <incomes v-if="currentState.left === 'incomes'"
                                         :active-income="activeIncome"
                                         :active-row="activeRow"
                                         @modify-income="modifyIncome"
                                         @modify-entry="modifyEntry">
                                </incomes>
                            </div>
                            <groups v-if="currentState.left === 'groups'"
                                    :active-group="activeGroup"
                                    :active-row="activeRow"
                                    @modify-group="modifyGroup"
                                    @modify-entry="modifyEntry">
                            </groups>
                        </div>
                    </div>
                </template>
                <template v-slot:right>
                    <div :class="rightVisibility" class="h-full">
                        <budget-stats v-if="!currentState.right" :budget="budget"/>
                        <income-form v-if="currentState.right === 'modify-income'" class="object-top" :income="currentState.data" @done="done"/>
                        <group-form v-if="currentState.right === 'modify-group'" class="object-top" :group="currentState.data" :budget="budget" @done="done"/>
                        <entry-form v-if="currentState.right === 'modify-entry'" class="object-top" :entry="currentState.data" :budget="budget" :incomes="incomes" :groups="groups" @done="done"/>
                        <budget-transactions v-if="currentState.right === 'transactions'" @done="done"></budget-transactions>
                    </div>
                </template>
            </budget-divided>
        </div>
        <modal v-if="currentState.right === 'budget-delete'"
               variant="danger"
               title="Are you sure?"
               confirm-label="Yes, Delete!"
               cancel-label="Oops! No"
               @confirm="budgetDeleteConfirm"
               @cancel="budgetDeleteCanceled">
            Do you really want to delete this budget? It can't be undone.
        </modal>
    </section>
</template>

<script>
    import BudgetHeader from '@/views/dashboard/budget/BudgetHeader'
    import BudgetRightHeader from '@/views/dashboard/budget/BudgetRightHeader'
    import BudgetSidebar from '@/components/budget/BudgetSidebar'
    import BudgetStats from '@/views/dashboard/budget/BudgetStats'
    import Incomes from '@/views/dashboard/budget/Incomes'
    import IncomeForm from '@/views/dashboard/budget/IncomeForm'
    import Groups from '@/views/dashboard/budget/Groups'
    import GroupForm from '@/views/dashboard/budget/GroupForm'
    import EntryForm from '@/views/dashboard/budget/EntryForm'
    import BudgetCreate from '@/views/dashboard/budget/BudgetCreate'
    import Modal from "@/components/ui/modal/Modal";
    import BudgetDivided from '@/components/budget/BudgetDivided'
    import BudgetTransactions from '@/views/dashboard/budget/BudgetTransactions'
    import moment from "moment"
    import { mapGetters, mapActions } from 'vuex'

    export default {

        components: {
            BudgetHeader,
            BudgetRightHeader,
            BudgetSidebar,
            BudgetStats,
            BudgetDivided,
            Incomes,
            IncomeForm,
            Groups,
            GroupForm,
            EntryForm,
            BudgetCreate,
            Modal,
            BudgetTransactions
        },

        data () {
            return {
                budgetLoaded: null,
                incomes: [],
                groups: [],
                otherGroups: [],
                state: {
                    left: null,
                    right: null,
                    data: null
                },
                activeIncome: null,
                activeGroup: null,
                activeRow: null
            }
        },

        computed: {
            ...mapGetters(['budget', 'settings']),

            currentState () {
                if (this.state.left === null) {
                    this.state.left = this.settings.view
                }
                return this.state
            },

            leftVisibility () {
                const classes = []
                if (this.state.right) {
                    classes.push('hidden md:inline')
                }
                return classes
            },

            rightVisibility () {
                const classes = []
                if (this.state.left === 'budget') {
                    classes.push('hidden md:inline')
                }
                return classes
            },

            budgetDate () {
                const year = this.$route.params.year
                const month = this.$route.params.month
                return `${year}-${month}-01`
            }

        },

        watch: {
            $route () {
                // Reload the budget when the route changes
                this.loadBudget()
            }
        },

        methods: {

            ...mapActions(['setBudget', 'refreshIncomeStats']),

            async viewChanged (view, reload) {
                if (reload) {
                    await this.loadBudget()
                }

                if (view === 'create-income') {
                    await this.incomeCreate()
                } else if (view === 'create-group') {
                    await this.groupCreate()
                } else if (view === 'delete-budget') {
                    await this.budgetDelete()
                } else if (view === 'incomes') {
                    await this.viewIncomes()
                } else if (view === 'groups') {
                    await this.viewGroups()
                } else {
                    await this.setState(view)
                }
            },

            async viewIncomes () {
                this.activeRow = null
                await this.$http.updateSettings({ view: 'incomes' })
                await this.loadBudget()
            },

            async viewGroups () {
                this.activeRow = null
                await this.$http.updateSettings({ view: 'groups' })
                await this.loadBudget()
            },

            async viewTransactions () {
                this.activeRow = null
                if (this.state.right === 'transactions') {
                    this.setState('budget')
                } else {
                    this.setState('budget', 'transactions')
                }
            },

            async redirectIfNoDate () {
                const year = this.$route.params.year
                const month = this.$route.params.month
                if (!year || !month) {
                    const date = moment(this.settings.month)
                    const year = date.format('YYYY')
                    const month = date.format('MM')
                    await this.$router.push({ name: 'budget', params: { year, month }})
                }
            },

            async loadBudget () {
                // If there is no date then create today's date and redirect
                await this.redirectIfNoDate()

                // Reset the relations if view is groups
                let relations = 'incomes'
                if (this.settings.view === 'groups') {
                    relations = 'groups'
                }
                try {
                    this.budgetLoaded = null
                    this.activeIncome = null
                    this.activeRow = null
                    await this.setBudget(null)
                    const budget = await this.$http.getBudget(this.budgetDate, relations)
                    await this.setBudget(budget.data)
                    await this.refreshIncomeStats()
                    this.incomes = budget.incomes
                    this.groups = budget.groups
                    // Set the last existing month in settings
                    await this.$http.updateSettings({ month: this.budget.month })
                    this.setState('budget')
                    this.budgetLoaded = true
                } catch (error) {
                    if (error.code === 404) {
                        await this.setBudget(null)
                    } else {
                        console.log('error', error)
                    }
                    this.budgetLoaded = false
                }
            },

            toggleRightPanel () {
                this.activeIncome = null
                this.activeGroup = null
                this.activeRow = null
                this.setState('budget')
            },

            setState (left = null, right = null, data = null) {
                if (left === 'budget') {
                    left = this.settings.view
                }
                this.state = { left, right, data }
            },

            async modifyIncome (income) {
                console.log('modifyIncome', income)
                this.activeRow = null
                if (this.activeIncome === income.id) {
                    this.activeIncome = null
                    this.setState('budget')
                } else {
                    this.activeIncome = income.id
                    this.setState('budget', 'modify-income', income)
                }
            },

            modifyGroup (group) {
                this.activeRow = null
                if (this.activeGroup === group.id) {
                    this.activeGroup = null
                    this.setState('budget')
                } else {
                    this.activeGroup = group.id
                    this.setState('budget', 'modify-group', group)
                }
            },

            async modifyEntry (entry) {
                this.activeIncome = null
                this.activeGroup = null
                if (entry.id !== null && entry.id === this.activeRow) {
                    this.activeRow = null
                    this.setState('budget')
                } else {
                    this.activeRow = entry.id
                    this.setState('budget', 'modify-entry', entry)
                }
            },

            async done (refresh) {
                console.log('done', refresh)
                this.setState('budget', null);
                if (refresh) {
                    await this.loadBudget()
                }
            },

            // async getIncomeStats () {
            //     console.log('getIncomeStats')
            //     const { data } = await this.$http.budgetStats(this.budgetDate, { income: true })
            //     return data.income || []
            // },

            incomeCreate () {
                this.setState('budget', 'modify-income', {
                    name: '',
                    dueDay: '1',
                    amount: '0.00'
                })
            },

            groupCreate () {
                this.setState('budget', 'modify-group', {
                    name: '',
                })
            },

            async budgetCreated (budget) {
                this.setState('budget')
                await this.loadBudget()
            },

            budgetDelete () {
                this.setState('budget', 'budget-delete')
            },

            budgetDeleteCanceled () {
                this.setState('budget')
            },

            async budgetDeleteConfirm () {
                try {
                    await this.$http.deleteBudget(this.budgetDate)
                    await this.loadBudget()
                    this.setState('budget')
                } catch ({ error }) {
                    console.log('error', error)
                    this.setState('budget')
                }
            }
        },

        async mounted () {
            await this.loadBudget()
        }

    }
</script>
