<template>
<!--    <div @click="showDetails = !showDetails" :class="classes">-->
    <div class="select-none">
        <div class="transaction-handle flex text-sm border rounded-md cursor-pointer m-1 p-2" :class="classes">
<!--            <div class="flex-none border-r pl-0 pr-1 pt-3">-->
<!--                <icon name="menu" fill size="4" class="cursor-move text-gray-300 hover:text-gray-700"></icon>-->
<!--            </div>-->
            <div class="flex-1">
                {{ transaction.name }}
                <div class="text-xs text-gray-400">
                    {{ transactionDate }}
                </div>
            </div>
            <div class="flex-none text-right">
                <div>
                    <span v-if="transaction.amount < 0" class="text-green-700 font-bold">{{ amount(transaction.amount) }}</span>
                    <span v-else class="text-gray-500">{{ amount(transaction.amount) }}</span>
                </div>
                <div v-if="transaction.pending" class="text-xs text-red-400">
                    pending
                </div>
            </div>
        </div>
<!--        <div v-if="showDetails" class="text-sm my-1">-->
<!--            <div class="flex border border-yellow-300 rounded-md p-1 mb-1">-->
<!--                <div class="flex-1">Categories</div>-->
<!--                <div class="flex-none text-right">-->
<!--                    <div v-for="category in categories">{{ category }}</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="flex border border-yellow-300 rounded-md p-1 mb-1">-->
<!--                <div class="flex-1">Payment Channel</div>-->
<!--                <div class="flex-none text-right">{{ transaction.paymentChannel }}</div>-->
<!--            </div>-->
<!--        </div>-->
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
                const classes = []

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
        },

        methods: {

            amount (value) {
                let number = value
                if (value >= 0) {
                    number = '-' + '$' + Math.abs(value).toFixed(2)
                } else {
                    number = '+' + '$' + Math.abs(value).toFixed(2)
                }

                return number
            },

            isDeposit(value) {
                return value < 0
            }

        }
    }

</script>
