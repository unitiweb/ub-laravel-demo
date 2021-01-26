<template>
    <div class="border border-gray-300 rounded-md shadow-md m-1">

        <budget-right-header title="Transactions" class="bg-gray-100">
            <template v-slot:left>
                <!--                <ub-button v-if="entry.id" @click="showDelete = true" outline variant="danger" size="sm" icon="trash" class="float-left"></ub-button>-->
            </template>
            <template v-slot:right>
                <ub-button variant="secondary" @click="refresh" icon="refresh" size="sm" outline/>
                <!--                <ub-button type="submit" size="sm">Save</ub-button>-->
            </template>
        </budget-right-header>

        <div class="overscroll-y-auto">
            <transaction v-for="transaction in transactions"
                              :key="`trans-${transaction.id}`"
                              :account="account"
                              :transaction="transaction">
            </transaction>
        </div>
    </div>
</template>

<script>
    import Transaction from '@/views/dashboard/banks/Transaction'
    import BudgetRightHeader from '@/views/dashboard/budget/BudgetRightHeader'
    import { mapActions } from 'vuex'

    export default {

        components: {
            Transaction,
            BudgetRightHeader
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

        watch: {
            account (value) {
                console.log('value', value)
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
                    console.log('data', data)
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
