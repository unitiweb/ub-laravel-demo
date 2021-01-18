<template>
    <div class="border border-gray-300 rounded-md shadow-md m-1">
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

    export default {

        components: {
            Transaction
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
            }
        },

        mounted () {
            this.loadTransactions()
        }

    }

</script>
