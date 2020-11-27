<template>
    <div>
        <income v-for="(income, index) in incomes"
                :key="`income-${index}`"
                :income="income"
                :active="isActive(income)"
                :active-row="activeRow"
                @modify-income="modifyIncome"
                @modify-entry="modifyEntry">
        </income>
    </div>
</template>

<script>
    import Income from '@/components/budget/Income'

    export default {

        components: {
            Income
        },

        props: {
            budget: {
                type: Object
            },
            activeIncome: {
                type: [String, Number],
                default: null
            },
            activeRow: {
                type: [String, Number],
                default: null
            }
        },

        data () {
            return {
                drag: false,
                balances: [],
                collapsed: {}
            }
        },

        computed: {

            year () {
                return this.$route.params.year
            },

            month () {
                return this.$route.params.month
            },

            budgetDate () {
                return `${this.year}-${this.month}-01`
            },

            incomes () {
                if (this.budget.unassignedIncomeEntries && this.budget.unassignedIncomeEntries.length >= 1) {
                    this.budget.incomes.push({
                        id: null,
                        unassigned: true,
                        name: 'Unassigned',
                        dueDay: null,
                        entries: this.budget.unassignedIncomeEntries
                    })
                }

                return this.budget.incomes
            }

        },

        methods: {

            modifyIncome (income) {
                this.$emit('modify-income', income)
            },

            async modifyEntry (entry) {
                this.$emit('modify-entry', entry)
            },

            isActive (income) {
                return income.id === this.activeIncome
            }

        }

    }
</script>
