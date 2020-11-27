<template>
    <card class="max-w-2xl" header="Start a New Budget" variant="primary">
        <icon name="trendingUp" class="text-green-700 mx-auto h-32 w-32"></icon>

        <div class="text-center pb-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Hello. You don't have a budget for {{ monthName }}
            </h3>
            <p class="mt-2 max-w-4xl text-sm text-gray-500">To create a budget we'll use the last budget as the default.</p>
        </div>
        <ul class="divide-y divide-gray-200 px-6">
            <li class="py-4 flex">
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">1. Get the last budget if one exists</p>
                </div>
            </li>

            <li class="py-4 flex">
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">2. Get all incomes and entries</p>
                </div>
            </li>

            <li class="py-4 flex">
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">3. Copy over all incomes and entries to new budget</p>
                </div>
            </li>
        </ul>

        <template v-slot:footer>
            <ub-button block size="xl" @click="start">Start {{ monthName }}'s Budget</ub-button>
        </template>
    </card>
</template>

<script>
    import moment from 'moment'

    export default {

        props: {
            month: {
                type: String
            }
        },

        computed: {
            monthName () {
                return moment(this.month).format('MMMM')
            }
        },

        methods: {

            async start () {
                try {
                    const { data } = await this.$http.addBudget(this.month)
                    this.$emit('created', data)
                } catch ({ error }) {
                    console.log('error', error)
                }
            }

        }

    }

</script>
