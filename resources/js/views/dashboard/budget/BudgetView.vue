<template>
    <section>
        <incomes v-if="budgetView === 'incomes'"/>
    </section>
</template>

<script>
import Incomes from '@/views/dashboard/budget/Incomes'

export default {

    components: {
        Incomes
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
            return this.year + '-' + this.month + '-01'
        }

    },

    data () {
        return {
            budget: {
                id: null,
                month: null,
                incomes: [],
                groups: []
            }
        }
    },

    methods: {

        async loadBudget () {
            let relations = 'incomes,incomes.entries,incomes.entries.group'

            // Reset the relations if view is groups
            if (this.$store.getters.budgetView === 'groups') {
                relations = 'groups,groups.entries,groups.entries.income'
            }

            try {
                const { data } = await this.$http.getBudget(this.budgetDate, relations)
                this.budget = data
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
