<template>
    <section>
        <budget-header class="mb-4"></budget-header>
<!--        <budget-sidebar>-->
            <div v-if="state.view === 'budget'">
                <incomes v-if="budgetView === 'incomes'" :budget="budget" @modify-entry="modifyEntry"/>
                <div class="text-red-500 text-right pr-4">
                    <f-button @click="budgetDelete" outline variant="danger" size="sm" icon-left="minusCircle">delete budget</f-button>
                </div>
            </div>
            <div v-else-if="state.view === 'modify-entry'">
                <entry-form :entry="state.data" :budget="budget" @done="entryDone"/>
            </div>
            <div v-else-if="state.view === 'budget-create'">
                <budget-create :month="budgetDate" @created="budgetCreated"></budget-create>
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
<!--        </budget-sidebar>-->
    </section>
</template>

<script>
    import BudgetHeader from '@/components/budget/BudgetHeader'
    import BudgetSidebar from '@/components/budget/BudgetSidebar'
    import Incomes from '@/views/dashboard/budget/Incomes'
    import EntryForm from '@/components/budget/EntryForm'
    import BudgetCreate from '@/views/dashboard/budget/BudgetCreate'
    import Modal from "@/components/ui/modal/Modal";

    export default {

        components: {
            BudgetHeader,
            BudgetSidebar,
            Incomes,
            EntryForm,
            BudgetCreate,
            Modal
        },

        computed: {

            year () {
                return this.$route.params.year
            },

            month () {
                return this.$route.params.month
            },

            budgetView () {
                return this.$store.getters.budgetView || 'incomes'
            },

            budgetDate () {
                return `${this.year}-${this.month}-01`
            }

        },

        data () {
            return {
                budget: {
                    id: null,
                    month: null,
                    incomes: [],
                    groups: []
                },
                state: {
                    view: 'budget',
                    data: null
                }
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
                let relations = 'incomes,incomes.entries,incomes.entries.group'

                // Reset the relations if view is groups
                if (this.budgetView === 'groups') {
                    relations = 'groups,groups.entries,groups.entries.income'
                }

                try {
                    const { data } = await this.$http.getBudget(this.budgetDate, relations)
                    this.budget = data
                    this.setState('budget')
                } catch ({ error }) {
                    if (error.code === 404) {
                        this.setState('budget-create')
                    } else {
                        console.log('error', error.code)
                    }
                }
            },

            setState (view, data = null) {
                this.state = { view, data }
            },

            modifyEntry (entry) {
                this.setState('modify-entry', entry)
            },

            entryDone (saved) {
                this.setState('budget');
                this.loadBudget()
            },

            budgetCreated (budget) {
                this.setState('budget')
                this.loadBudget()
            },

            async budgetDelete () {
                this.setState('budget-delete')
            },

            budgetDeleteCanceled () {
                this.setState('budget')
            },

            async budgetDeleteConfirm () {
                try {
                    await this.$http.deleteBudget(this.budgetDate)
                    await this.loadBudget()
                } catch ({ error }) {
                    console.log('error', error)
                }
            }

        },

        async mounted () {
            await this.loadBudget()
        }

    }
</script>
