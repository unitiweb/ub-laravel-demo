<template>
    <section>
        <budget-header class="mb-4" :view="currentState.left" @view-changed="viewChanged"></budget-header>
        <div class="px-4 pb-4">
            <div v-if="hasBudget === false">
                <budget-create :month="budgetDate" @created="budgetCreated"></budget-create>
            </div>
            <budget-divided v-if="hasBudget === true">
                <template v-slot:left>
                    <div :class="leftVisibility">
                        <div v-if="['incomes', 'groups'].includes(currentState.left)">
                            <budget-right-header>
                                <template v-slot:left>
                                    <ub-button @click="viewIncomes" size="sm" icon-left="currencyDollar" :variant="currentState.left === 'incomes' ? 'primary' : 'secondary'" outline></ub-button>
                                    <ub-button @click="viewGroups" size="sm" icon-left="viewBoard" :variant="currentState.left === 'groups' ? 'success' : 'secondary'" outline></ub-button>
                                </template>
                            </budget-right-header>
                            <incomes v-if="currentState.left === 'incomes'" :active-income="activeIncome" :active-row="activeRow" :budget="budget" @modify-income="modifyIncome" @modify-entry="modifyEntry"/>
                            <groups v-if="currentState.left === 'groups'" :active-group="activeGroup" :active-row="activeRow" :budget="budget" @modify-group="modifyGroup" @modify-entry="modifyEntry"/>
                        </div>
                    </div>
                </template>
                <template v-slot:right>
                    <div :class="rightVisibility">
                        <budget-stats v-if="!currentState.right" :budget="budget"/>
                        <income-form v-if="currentState.right === 'modify-income'" class="object-top" :income="currentState.data" :budget="budget" @done="done"/>
                        <group-form v-if="currentState.right === 'modify-group'" class="object-top" :group="currentState.data" :budget="budget" @done="done"/>
                        <entry-form v-if="currentState.right === 'modify-entry'" class="object-top" :entry="currentState.data" :incomes="incomes" :groups="groups" @done="done"/>
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
    import BudgetHeader from '@/components/budget/BudgetHeader'
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
    import TransitionSlide from '@/components/transitions/TransitionSlide'
    import moment from "moment";

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
            TransitionSlide
        },

        data () {
            return {
                budget: null,
                hasBudget: null,
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

            currentState () {
                if (this.state.left === null) {
                    this.state.left = this.budgetView
                }
                return this.state
            },

            leftVisibility () {
                const classes = []

                if (this.state.right) {
                    classes.push('hidden md:inline')
                } else {
                    classes.push('')
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

            year () {
                return this.$route.params.year
            },

            month () {
                return this.$route.params.month
            },

            budgetView () {
                return this.$store.getters.lastView || 'incomes'
            },

            budgetDate () {
                return `${this.year}-${this.month}-01`
            }

        },

        watch: {
            $route () {
                // Reload the budget when the route changes
                this.loadBudget()
            }
        },

        methods: {

            async viewChanged (view, reload) {
                if (view === 'create-income') {
                    await this.incomeCreate()
                } else if (view === 'create-group') {
                    await this.groupCreate()
                } else if (view === 'delete-budget') {
                    await this.budgetDelete()
                } else if (view === 'incomes') {
                    console.log('view', view)
                    await this.viewIncomes()
                } else if (view === 'groups') {
                    console.log('view', view)
                    await this.viewGroups()
                } else {
                    await this.setState(view)
                }
                if (reload) {
                    await this.loadBudget()
                }
            },

            async viewIncomes () {
                await this.$http.updateSettings({ view: 'incomes' })
                await this.$store.dispatch('lastView', 'incomes');
                await this.loadBudget()
            },

            async viewGroups () {
                await this.$http.updateSettings({ view: 'groups' })
                await this.$store.dispatch('lastView', 'groups');
                await this.loadBudget()
            },

            async redirectIfNoDate () {
                if (!this.year || !this.month) {
                    const date = moment(this.$store.getters.lastMonth)
                    const year = date.format('YYYY')
                    const month = date.format('MM')
                    await this.$router.push({ name: 'budget', params: { year, month }})
                }
            },

            async loadBudget () {
                // If there is no date then create today's date and redirect
                await this.redirectIfNoDate()

                this.hasBudget = null

                // Reset the relations if view is groups
                let relations = 'incomes'
                if (this.budgetView === 'groups') {
                    relations = 'groups'
                }

                try {
                    this.budget = null
                    const budget = await this.$http.getBudget(this.budgetDate, relations)
                    this.budget = budget.data
                    this.incomes = budget.incomes
                    this.groups = budget.groups
                    // Set the last existing month in settings
                    await this.$http.updateSettings({ month: this.budget.month })
                    this.hasBudget = true
                    this.setState('budget')
                } catch (error) {
                    if (error.code === 404) {
                        this.budget = null
                    } else {
                        console.log('error', error.code)
                    }
                    this.hasBudget = false
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
                    left = this.budgetView
                }
                this.state = { left, right, data }
            },

            modifyIncome (income) {
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
                this.setState('budget', null);
                if (refresh) {
                    await this.loadBudget()
                }
                this.activeIncome = null
                this.activeRow = null
            },

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
