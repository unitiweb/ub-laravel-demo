<template>
    <div @click="showDetails = !showDetails" :class="classes">
        <div class="flex">
            <div class="flex-1">
                {{ transaction.name }}
                <div class="text-xs text-gray-400">
                    {{ transactionDate }}
                </div>
            </div>
            <div class="flex-none text-right">
                <div :class="{ 'text-blue-600': transaction.amount < 0 }">{{ transaction.amount | currency }}</div>
                <div v-if="transaction.pending" class="text-xs text-red-400">
                    pending
                </div>
            </div>
        </div>
        <div v-if="showDetails" class="text-sm my-1">
            <div class="flex border border-yellow-300 rounded-md p-1 mb-1">
                <div class="flex-1">Categories</div>
                <div class="flex-none text-right">
                    <div v-for="category in categories">{{ category }}</div>
                </div>
            </div>
            <div class="flex border border-yellow-300 rounded-md p-1 mb-1">
                <div class="flex-1">Payment Channel</div>
                <div class="flex-none text-right">{{ transaction.paymentChannel }}</div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment"

    export default {

        props: {
            account: {
               type: Object
            },
            transaction: {
                type: Object
            }
        },

        data () {
            return {
                showDetails: false
            }
        },

        computed: {
            categories () {
                return this.transaction.category.split(':')
            },
            classes () {
                const classes = ['text-sm border rounded-md cursor-pointer m-1 p-1']

                if (this.showDetails) {
                    classes.push('border-yellow-300 bg-yellow-50 hover:bg-yellow-100')
                } else {
                    classes.push('border-gray-200 bg-gray-50 hover:bg-gray-100')
                }

                return classes
            },

            category () {
                const categories = this.transaction.category.split(':');
                return categories[(categories.length - 1)]
                // return this.transaction.category.replace(':', ' / ')
            },

            transactionDate () {
                return moment(this.transaction.transactionDate).format('YYYY-MM-DD')
            }
        }
    }

</script>
