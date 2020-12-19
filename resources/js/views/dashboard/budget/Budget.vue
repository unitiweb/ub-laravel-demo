<template>
    <section>
        <budget-header class="mb-4" :view="currentState.left" @view-changed="viewChanged"></budget-header>
        <div class="px-4 pb-4">
            <div v-if="!budget">
                <budget-create :month="budgetDate" @created="budgetCreated"></budget-create>
            </div>
            <budget-divided v-if="budget">
                <template v-slot:left>
                    <div :class="leftVisibility">
                        <div v-if="['incomes', 'groups'].includes(currentState.left)">
                            <div class="flex align-middle p-1 m-1">
                                <div class="flex-initial">
                                    <ub-button @click="viewIncomes" size="sm" icon="currencyDollar" :variant="currentState.left === 'incomes' ? 'primary' : 'secondary'" outline></ub-button>
                                    <ub-button @click="viewGroups" size="sm" icon="viewBoard" :variant="currentState.left === 'groups' ? 'success' : 'secondary'" outline></ub-button>
                                </div>
                                <div class="flex-initial ml-2 pt-1 font-bold">
                                    <span  v-if="currentState.left === 'incomes'">View by Income</span>
                                    <span  v-if="currentState.left === 'groups'">View by Group</span>
                                </div>
                            </div>
                            <incomes v-if="currentState.left === 'incomes'" :active-income="activeIncome" :active-row="activeRow" :budget="budget" @modify-income="modifyIncome" @modify-entry="modifyEntry"/>
                            <groups v-if="currentState.left === 'groups'" :active-group="activeGroup" :active-row="activeRow" :budget="budget" @modify-group="modifyGroup" @modify-entry="modifyEntry"/>
                        </div>
                    </div>
                </template>
                <template v-slot:right>
                    <div :class="rightVisibility">
                        <budget-stats v-show="!currentState.right" :budget="budget"/>
                        <income-form v-show="currentState.right === 'modify-income'" class="object-top" :income="currentState.data" :budget="budget" @done="done"/>
                        <group-form v-show="currentState.right === 'modify-group'" class="object-top" :group="currentState.data" :budget="budget" @done="done"/>
                        <entry-form v-show="currentState.right === 'modify-entry'" class="object-top" :entry="currentState.data" :incomes="incomes" :groups="groups" :other-groups="otherGroups" @done="done"/>
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
                budget: {
                    id: null,
                    month: null
                },
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
                    console.log('state', this.state)
                    classes.push('hidden md:inline')
                } else {
                    // classes.push('hidden md:inline')
                    classes.push('')
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
                if (reload) {
                    await this.loadBudget()
                }
                if (view === 'create-income') {
                    this.incomeCreate()
                } else if (view === 'create-group') {
                    this.groupCreate()
                } else if (view === 'delete-budget') {
                    this.budgetDelete()
                } else {
                    this.setState(view)
                }
            },

            async viewIncomes () {
                await this.$http.updateSettings({ lastView: 'incomes' })
                await this.$store.dispatch('lastView', 'incomes');
                await this.loadBudget()
            },

            async viewGroups () {
                await this.$http.updateSettings({ lastView: 'groups' })
                await this.$store.dispatch('lastView', 'groups');
                await this.loadBudget()
            },

            async redirectIfNoDate () {
                if (!this.year || !this.month) {
                    const now = moment()
                    const year = now.format('YYYY')
                    const month = now.format('MM')
                    await this.$router.push({ name: 'budget', params: { year, month }})
                }
            },

            async loadBudget () {
                // If there is no date then create today's date and redirect
                await this.redirectIfNoDate()

                // Reset the relations if view is groups
                let relations = 'incomes,incomes.entries,incomes.entries.group,unassignedIncomeEntries'
                if (this.budgetView === 'groups') {
                    relations = 'groups,groups.entries,groups.entries.group,groups.entries.income,unassignedGroupEntries'
                }

                try {
                    this.budget = {}
                    const budget = await this.$http.getBudget(this.budgetDate, relations)
                    this.budget = budget.data
                    this.incomes = budget.incomes
                    this.groups = budget.groups.budget
                    this.otherGroups = budget.groups.other
                    this.setState('budget')
                } catch ({ error }) {
                    if (error.code === 404) {
                        this.budget = null
                    } else {
                        console.log('error', error.code)
                    }
                }
            },

            toggleRightPanel () {
                this.activeIncome = null
                this.activeGroup = null
                this.activeRow = null
                this.setState('budget')
            },

            setState (left = null, right = null, data = null) {
                if (left === 'budget') left = this.budgetView
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

            modifyEntry (entry) {
                this.activeIncome = null
                this.activeGroup = null
                if (entry.id !== null && entry.id === this.activeRow) {
                    this.activeRow = null
                    this.setState('budget')
                } else {
                    this.activeRow = entry.id
                    this.state.right = 'modify-entry'
                    this.state.data = entry
                    this.setState('budget', 'modify-entry', entry)
                }
            },

            async done () {
                this.setState('budget', null);
                await this.loadBudget()
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
