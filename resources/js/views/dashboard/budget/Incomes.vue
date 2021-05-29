<template>
    <div>
        <income v-for="(income, index) in budget.incomes"
                :key="`income-${index}`"
                :income="income"
                :active="isActive(income)"
                :active-row="activeRow"
                @modify-income="modifyIncome"
                @modify-entry="modifyEntry">
        </income>
        <income v-for="(income, index) in unassignedIncomeEntries"
                :key="`unassigned-income-${index}`"
                :income="income"
                :active="isActive(income)"
                :active-row="activeRow"
                @modify-income="modifyIncome"
                @modify-entry="modifyEntry">
        </income>
    </div>
</template>

<script>
    import Income from '@/views/dashboard/budget/Income'
    import { mapGetters } from 'vuex'

    export default {

        components: {
            Income
        },

        props: {
            activeIncome: {
                type: [String, Number],
                default: null
            },
            activeRow: {
                type: [String, Number],
                default: null
            }
        },

        computed: {
            ...mapGetters(['budget']),

            budgetDate () {
                const year = this.$route.params.year
                const month = this.$route.params.month
                return `${year}-${month}-01`
            },

            unassignedIncomeEntries () {
                const incomes = []
                if (this.budget.unassignedIncomeEntries && this.budget.unassignedIncomeEntries.length >= 1) {
                    incomes.push({
                        id: null,
                        unassigned: true,
                        name: 'Unassigned',
                        dueDay: null,
                        entries: this.budget.unassignedIncomeEntries
                    })
                }

                return incomes
            },

            dragOptions() {
                return {
                    animation: 200,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                }
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
