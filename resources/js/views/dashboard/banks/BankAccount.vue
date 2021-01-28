<template>
    <div class="border border-gray-300 rounded-md shadow-md m-1">
        <budget-right-header title="Transactions" class="bg-gray-100">
            <template v-slot:left>
<!--                <ub-button v-if="entry.id" @click="showDelete = true" outline variant="danger" size="sm" icon="trash" class="float-left"></ub-button>-->
            </template>
            <template v-slot:right>
<!--                <ub-button variant="secondary" @click="refresh" icon="refresh" size="sm" outline/>-->
<!--                <ub-button type="submit" size="sm">Save</ub-button>-->
            </template>
        </budget-right-header>

        <div v-if="transactions.length === 0" class="p-4 m-1 text-center bg-gray-50 border border-gray-200 rounded-md">
            No Transactions
        </div>
        <transaction v-for="transaction in transactions"
                     :key="`trans-${transaction.id}`"
                     :account="account"
                     :transaction="transaction">
        </transaction>
    </div>
</template>

<script>
    import Transaction from '@/views/dashboard/banks/Transaction'
    import BudgetRightHeader from '@/views/dashboard/budget/BudgetRightHeader'
    import Draggable from 'vuedraggable'
    import { mapActions } from 'vuex'

    export default {

        components: {
            Transaction,
            BudgetRightHeader,
            Draggable
        },

        props: {
            account: {
                type: Object
            }
        },

        data () {
            return {
                transactions: []
            }
        },

        computed: {
            dragOptions() {
                return {
                    animation: 200,
                    group: "transactions",
                    disabled: false,
                    ghostClass: "ghost"
                };
            },

            /**
             * Triggered when an entry row drag is dropped
             */
            dragChanged (evt) {
                // if (evt.added) {
                //     this.addedTo(evt.added.newIndex, evt.added.element)
                // } else if (evt.moved) {
                //     this.moveTo(evt.moved.oldIndex, evt.moved.newIndex, evt.moved.element)
                // }
            }
        },

        watch: {
            account (value) {
                this.loadTransactions()
            }
        },

        methods: {
            ...mapActions(['setLoading']),

            async loadTransactions () {
                try {
                    const { data } = await this.$http.financialTransactions(
                        this.account.bankInstitutionId,
                        this.account.id
                    )
                    this.transactions = data
                } catch ({ error }) {
                    console.log('error', error)
                }
            },

            async refresh () {
                await this.setLoading(true)
                await this.$http.financialTransactionsStore(this.account.bankInstitutionId, this.account.id)
                await this.loadTransactions()
                await this.setLoading(false)
            }
        },

        mounted () {
            this.loadTransactions()
        }

    }

</script>


<style lang="scss" scoped>
    .flip-list-move {
        transition: transform 0.5s;
    }
    .no-move {
        transition: transform 0s;
    }
    .ghost {
        visibility: hidden;
    }
    .list-group {
        min-height: 20px;
    }
    .list-group-item {
        cursor: move;
    }
    .list-group-item i {
        cursor: pointer;
    }
</style>
