<template>
    <drag-element action="assign-transaction" :draggable="isDraggable" :element-data="dragPayload">
        <div class="transaction-handle select-none text-sm border rounded-md mb-2" :class="classes">
            <div class="flex m-1 p-1 pb-0">
                <div v-if="isDraggable" class="flex-none border-r pl-0 pr-1 pt-2">
                    <icon name="menu" fill size="4" class="cursor-move text-gray-300 hover:text-gray-700"></icon>
                </div>
                <div class="flex-1 pl-2">
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
            <div v-if="transaction.income" class="py-1 mt-1 text-xs bg-green-100 text-green-600 border border-green-200 border-b-0 border-l-0 border-r-0 rounded-b-md">
                <div class="px-2">
                    <div class="flex">
                        <div class="flex-none">{{ transaction.income.name }}</div>
                        <div class="flex-1 text-center">{{ makeBudgetDate(transaction.income.budget.month) }}</div>
                        <div class="flex-none text-right">+{{ transaction.income.amount | currency }}</div>
                    </div>
                </div>
            </div>
            <div v-if="transaction.entries && transaction.entries.length > 0" class="py-1 mt-1 text-xs bg-green-100 text-green-600 border border-green-200 border-b-0 border-l-0 border-r-0 rounded-b-md">
                <div v-for="entry in transaction.entries" :key="`entry-${entry.id}`" class="px-2">
                    <div class="flex">
                        <div class="flex-none">{{ entry.name }}</div>
                        <div class="flex-1 text-center">{{ makeBudgetDate(entry.budget.month) }}</div>
                        <div class="flex-none text-right">-{{ entry.amount | currency }}</div>
                    </div>
                </div>
            </div>
        </div>
    </drag-element>
</template>

<script>
    import DragElement from '@/components/ui/dragdrop/DragElement';
    import moment from "moment"

    export default {

        components: {
            DragElement
        },

        props: {
            account: {
               type: Object
            },
            transaction: {
                type: Object
            },
            draggable: {
                type: Boolean,
                default: true
            }
        },

        data () {
            return {
                showDetails: false
            }
        },

        computed: {
            dragPayload () {
                if (this.isDraggable) {
                    return {
                        account: this.account,
                        transaction: this.transaction
                    }
                }
            },
            categories () {
                return this.transaction.category.split(':')
            },

            isAvailable () {
                if (this.transaction && this.transaction.entries && this.transaction.entries.length > 0) {
                    return false
                } else if (this.transaction && this.transaction.income) {
                    return false
                }

                return true
            },

            isDraggable () {
                if (this.draggable) {
                    return this.isAvailable;
                }

                return false
            },
            classes () {
                const classes = []

                if (this.showDetails) {
                    classes.push('border-yellow-300 bg-yellow-50 hover:bg-yellow-100')
                } else if (!this.isAvailable) {
                    classes.push('bg-green-50 border-green-200 bg-green-50')
                } else {
                    classes.push('border-gray-200 bg-gray-50 hover:bg-gray-100')
                }

                if (this.isDraggable) {
                    classes.push('cursor-pointer')
                } else {
                    // classes.push('pl-2')
                }

                return classes
            },

            category () {
                const categories = this.transaction.category.split(':');
                return categories[(categories.length - 1)]
                // return this.transaction.category.replace(':', ' / ')
            },

            transactionDate () {
                return moment(this.transaction.transactionDate).format('MMMM Do')
            }
        },

        methods: {
            makeBudgetDate (budgetDate) {
                if (budgetDate) {
                    return moment(budgetDate).format('MMMM') + ' budget'
                }
                return ''
            },

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
