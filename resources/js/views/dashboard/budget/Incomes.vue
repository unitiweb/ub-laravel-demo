<template>
    <div>
        <income v-for="(income, index) in budget.incomes"
                :key="`income-${index}`"
                :income="income"
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
                return this.budget.incomes
            }

        },

        methods: {

            async modifyEntry (entry) {
                this.$emit('modify-entry', entry)
            }

        }

    }
</script>
