<template>
    <section>
        <budget-header class="mb-4"></budget-header>

        <div class="px-4 pb-4">
            <div v-if="!budget">
                <budget-create :month="budgetDate" @created="budgetCreated"></budget-create>
            </div>

            <budget-divided v-if="budget">
                <template v-slot:left>
                    <div :class="leftVisibility">
                        <incomes v-if="budgetView === 'incomes'" :active-income="activeIncome" :active-row="activeRow" :budget="budget" @modify-income="modifyIncome" @modify-entry="modifyEntry"/>
                        <groups v-if="budgetView === 'groups'" :active-income="activeGroup" :active-row="activeRow" :budget="budget" @modify-group="modifyGroup" @modify-entry="modifyEntry"/>
                        <div>
                            <ub-button @click="incomeCreate" class="float-left" outline variant="primary" size="sm" icon-left="plusCircle">Create Income</ub-button>
                            <ub-button @click="budgetDelete" class="float-right" outline variant="danger" size="sm" icon-left="minusCircle">delete budget</ub-button>
                        </div>
                    </div>
                </template>
                <template v-slot:right>
                    <div :class="rightVisibility">
                        <!--                    <transition-slide :out="state.view !== 'budget'">-->
                        <div v-show="state.view === 'budget'" class="object-top p-4 text-center">
                            Stats will go here
                        </div>
                        <!-- </transition-slide>-->
                        <!-- <transition-slide :out="state.view !== 'modify-income'">-->
                        <income-form v-show="state.view === 'modify-income'" class="object-top" :income="state.data" :budget="budget" @done="done"/>
                        <!-- </transition-slide>-->
                        <!-- <transition-slide :out="state.view !== 'modify-income'">-->
                        <group-form v-show="state.view === 'modify-group'" class="object-top" :group="state.data" :budget="budget" @done="done"/>
                        <!-- </transition-slide>-->
                        <!-- <transition-slide :out="state.view !== 'modify-entry'">-->
                        <entry-form v-show="state.view === 'modify-entry'" class="object-top" :entry="state.data" :incomes="incomes" :groups="groups" @done="done"/>
                        <!-- </transition-slide>-->
                    </div>
                </template>
            </budget-divided>
        </div>

        <modal v-if="state.view === 'budget-delete'"
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
    import Incomes from '@/views/dashboard/budget/Incomes'
    import IncomeForm from '@/views/dashboard/budget/IncomeForm'
    import Groups from '@/views/dashboard/budget/Groups'
    import GroupForm from '@/views/dashboard/budget/GroupForm'
    import EntryForm from '@/views/dashboard/budget/EntryForm'
    import BudgetCreate from '@/views/dashboard/budget/BudgetCreate'
    import Modal from "@/components/ui/modal/Modal";
    import BudgetDivided from '@/components/budget/BudgetDivided'
    import TransitionSlide from '@/components/transitions/TransitionSlide'

    export default {

        components: {
            BudgetHeader,
            BudgetSidebar,
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
                    month: null,
                    incomes: [],
                    groups: []
                },
                incomes: [],
                groups: [],
                state: {
                    view: 'budget',
                    title: 'Budget Stats',
                    data: null
                },
                activeIncome: null,
                activeGroup: null,
                activeRow: null
            }
        },

        computed: {

            leftVisibility () {
                const classes = []

                if (this.state.view !== 'budget') {
                    classes.push('hidden md:inline')
                } else {
                    classes.push('')
                }

                return classes
            },

            rightVisibility () {
                const classes = []
                if (this.state.view === 'budget') {
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
            $route() {
                // Reload the budget when the route changes
                this.loadBudget()
            }
        },

        methods: {

            async loadBudget () {
                let relations = 'incomes,incomes.entries,incomes.entries.group,unassignedIncomeEntries'

                // Reset the relations if view is groups
                if (this.budgetView === 'groups') {
                    relations = 'groups,groups.entries,groups.entries.group,groups.entries.income,unassignedGroupEntries'
                }

                try {
                    const { data } = await this.$http.getBudget(this.budgetDate, relations)
                    this.budget = data
                    this.setState('budget')
                } catch ({ error }) {
                    if (error.code === 404) {
                        this.budget = null
                        // this.setState('budget-create')
                    } else {
                        console.log('error', error.code)
                    }
                }
            },
            async loadData () {
                try {
                    const { data: incomes } = await this.$http.getIncomes(this.budget.month)
                    const { data: groups } = await this.$http.getGroups()

                    this.incomes = []
                    for (let i = 0; i < incomes.length; i++) {
                        this.incomes.push({ id: incomes[i].id, label: incomes[i].name })
                    }
                    this.incomes.push({ id: null, label: 'none' })

                    this.groups = []
                    for (let i = 0; i < groups.length; i++) {
                        this.groups.push({ id: groups[i].id, label: groups[i].name })
                    }
                    this.groups.push({ id: null, label: 'none' })

                } catch (error) {
                    console.log(error)
                }
            },

            toggleRightPanel () {
                if (this.state.view !== 'budget') {
                    this.activeIncome = null
                    this.activeRow = null
                    this.setState('budget')
                }
            },

            setState (view, title = 'Budget Stats', data = null) {
                this.state = { view, title, data }
            },

            modifyIncome (income) {
                this.activeRow = null
                if (this.activeIncome === income.id) {
                    this.activeIncome = null
                    this.setState('budget')
                } else {
                    console.log('income', income)
                    this.activeIncome = income.id
                    this.setState('modify-income', 'Modify Income', income)
                }
            },

            modifyGroup (group) {
                this.setState('modify-group', 'Modify Group', group)
                // this.activeRow = null
                // if (this.activeIncome === income.id) {
                //     this.activeIncome = null
                //     this.setState('budget')
                // } else {
                //     console.log('income', income)
                //     this.activeIncome = income.id
                //     this.setState('modify-income', 'Modify Income', income)
                // }
            },

            modifyEntry (entry) {
                console.log('here', entry)
                this.activeIncome = null

                if (entry.id === null) {
                    this.setState('modify-entry', 'Create Entry', entry)
                } else if (entry.id === this.activeRow) {
                    this.activeRow = null
                    this.setState('budget')
                } else {
                    this.activeRow = entry.id
                    this.setState('modify-entry', 'Modify Entry', entry)
                }
            },

            done () {
                this.setState('budget');
                this.loadBudget()
                this.activeIncome = null
                this.activeRow = null
            },

            incomeCreate () {
                this.setState('modify-income', 'Create Income', {
                    name: '',
                    dueDay: '1',
                    amount: '0.00'
                })
            },

            budgetCreated (budget) {
                this.setState('budget')
                this.loadBudget()
            },

            async budgetDelete () {
                this.setState('budget-delete', 'Delete Entry')
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
            await this.loadData()
        }

    }
</script>
