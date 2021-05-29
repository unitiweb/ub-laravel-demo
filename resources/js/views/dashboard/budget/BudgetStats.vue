<template>
    <div>
        <budget-right-header></budget-right-header>
        <div class="bg-gray-100 border border-gray-300 rounded-md shadow-md p-4 grid grid-cols-2 gap-6">
            <div class="col-span-2 text-lg text-center font-bold">Budget Stats</div>
            <div class="col-span-2">
                <dl class="grid grid-cols-1 rounded-lg bg-white overflow-hidden shadow divide-y divide-gray-200">
                    <div v-for="item in stats" :key="item.name" class="px-4 p-6">
                        <dt class="text-base font-normal text-gray-900">
                            {{ item.name }}
                        </dt>
                        <dd class="mt-1 flex justify-between items-baseline">
                            <div class="flex items-baseline text-xl font-semibold text-indigo-600">
                                {{ item.amount | currency }}
                                <span v-if="item.total" class="ml-2 text-sm font-medium text-gray-400"> of {{ item.total | currency }} </span>
                            </div>
                            <div v-if="item.percent" class="bg-green-100 text-green-800 inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium">
                                {{ item.percent | percent }}
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</template>

<script>
    import BudgetRightHeader from '@/views/dashboard/budget/BudgetRightHeader'
    import { mapGetters } from 'vuex'

    export default {
        components: {
            BudgetRightHeader
        },

        computed: {
            ...mapGetters(['incomeStats']),

            subTotals () {
                return this.incomeStats.subTotals || null
            },

            percents () {
                return this.incomeStats.percents || null
            },

            totals () {
                return this.incomeStats.totals || null
            },

            stats () {
                return [
                    {
                        name: 'Income',
                        amount: this.subTotals.amount,
                        percent: this.percents.amount,
                        total: this.totals.amount,
                    }, {
                        name: 'Expenses',
                        amount: this.subTotals.expenses,
                        percent: this.percents.expenses,
                        total: this.totals.expenses,
                    }, {
                        name: 'Out Standing',
                        amount: this.subTotals.outStanding,
                        percent: null,
                        total: null,
                    }, {
                        name: 'Left Over',
                        amount: this.subTotals.leftOver,
                        percent: null,
                        total: null,
                    }
                ]
            }
        }
    }

</script>
