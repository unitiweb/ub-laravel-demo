<template>
    <section>
        <bank-header @refresh="refresh" @institution-added="showInstitutionAddedDialog = true"></bank-header>
        <div class="p-4">
            <budget-divided>
                <template v-slot:left>
                    <institutions @modify-account="modifyAccount" :active="activeAccount"></institutions>
                </template>
                <template v-slot:right>
                    <bank-stats v-if="state.right === 'stats'" :budget="{}"/>
                    <bank-account v-if="state.right === 'transactions'" :account="state.data"/>
                </template>
            </budget-divided>
        </div>
        <modal v-if="showInstitutionAddedDialog"
               variant="info"
               title="Bank Account Added!"
               confirm-label="Okay"
               hide-cancel
               @confirm="institutionAddedConfirm">
            The bank institution has been added to your account.
            If it doesn't show right away, then wait a minute and click the refresh button.
        </modal>
    </section>
</template>

<script>
    import BankHeader from '@/views/dashboard/banks/BankHeader'
    import BudgetDivided from '@/components/budget/BudgetDivided'
    import Institutions from '@/views/dashboard/banks/Institutions'
    import BankStats from '@/views/dashboard/banks/BankStats'
    import BankAccount from '@/views/dashboard/banks/BankAccount'
    import BudgetRightHeader from '@/views/dashboard/budget/BudgetRightHeader'
    import Modal from '@/components/ui/modal/Modal'
    import { mapGetters, mapActions } from 'vuex'

    export default {

        components: {
            BankHeader,
            BudgetDivided,
            Institutions,
            BankStats,
            BankAccount,
            BudgetRightHeader,
            Modal
        },

        data () {
            return {
                showInstitutionAddedDialog: false,
                activeAccount: null,
                state: {
                    right: 'stats',
                    data: null
                }
            }
        },

        computed: {
            ...mapGetters(['bankInstitutions'])
        },

        methods: {
            ...mapActions(['setBankInstitutions']),

            setState (right, data) {
                this.state = {right, data}
            },

            async loadInstitutions () {
                try {
                    this.loading = true
                    const { data } = await this.$http.financialInstitutions('accounts')
                    this.setBankInstitutions(data)
                    this.loading = false
                    this.loaded = true
                } catch ({ error }) {
                    console.log('error', error)
                    this.loading = false
                }
            },

            refresh () {
                this.loadInstitutions()
            },

            institutionAddedConfirm () {
                this.refresh()
                this.showInstitutionAddedDialog = false
            },

            success () {
                // this.loadInstitutions()
                const max = 20
                let count = 0;
                this.showLoading = true
                const institutionsCount = this.bankInstitutions.length
                const interval = setInterval(() => {
                    if (this.bankInstitutions.length > institutionsCount || count === max) {
                        this.showLoading = false
                        if (count === max) {
                            this.showRefreshAlert = true
                        }
                        clearInterval(interval);
                    }
                    count += 1
                }, 1000);
            },

            modifyAccount (account) {
                if (account.id === this.activeAccount) {
                    this.activeAccount = null
                } else {
                    this.activeAccount = account.id
                }
                this.setState('transactions', account)
            }
        },

        mounted () {
            this.loadInstitutions()
        }

    }

</script>
