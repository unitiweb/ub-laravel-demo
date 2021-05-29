<template>
    <drop-zone @dropped="dropped" :opacity="0.2">
        <div :class="rowClasses" class="flex border-b rounded-md rounded-b-none cursor-pointer m-1 pb-1">
            <div class="flex-none border-r px-1 pt-4">
                <icon name="menu" fill size="4" class="entry-handle cursor-move text-gray-300 hover:text-gray-700"></icon>
            </div>
            <div @click="modify" class="flex-1 px-2 pt-1">
                {{ entry.name }}
                <div class="text-xs text-gray-600">
                    Due Day: {{ dueDate }}
                </div>
            </div>
            <div @click="modify" class="flex-none text-right px-2 pt-1">
                {{ entry.amount | currency }}
                <div class="text-xs text-gray-600">
                    {{ stateLabel }}
                </div>
            </div>
            <div v-if="!hideProgress" class="flex-none rounded-md pt-2 pr-2">
                <entry-progress v-model="entry" :month="this.month" @updated="updateStatus"></entry-progress>
            </div>
        </div>
        <modal v-if="dialog === 'not-equal'" variant="warning" title="Amounts are not equal" hide-cancel confirm-label="Okay" @confirm="dialog = null">
            The transaction can not be used. The transaction's amount is different from this entry's amount.
        </modal>
        <modal v-if="dialog === 'is-deposit'" variant="warning" title="Transaction is a Deposit" hide-cancel confirm-label="Okay" @confirm="dialog = null">
            The transaction is a deposit, so it cannot be used for this entry.
        </modal>
    </drop-zone>
</template>

<script>
    import Currency from '@/components/ui/Currency'
    import DueDay from '@/components/ui/DueDay'
    import EntryProgress from '@/views/dashboard/budget/EntryProgress'
    import { cleanNumber } from '@/scripts/helpers/utils'
    import EditInPlace from '@/components/ui/form/EditInPlace'
    import DropZone from '@/components/ui/dragdrop/DropZone'
    import Modal from '@/components/ui/modal/Modal'
    import moment from 'moment'
    import { mapActions } from 'vuex'

    export default {

        components: {
            Currency,
            DueDay,
            EntryProgress,
            EditInPlace,
            DropZone,
            Modal
        },

        props: {
            month: {
                type: String
            },
            entry: {
                type: Object
            },
            active: {
                type: Boolean,
                default: false
            },
            hideProgress: {
                type: Boolean,
                default: false
            }
        },

        data () {
            return {
                edit: null,
                dialog: null
            }
        },

        computed: {
            date () {
                const year = this.$route.params.year
                const month = this.$route.params.month
                let dueDay = this.entry.dueDay ? this.entry.dueDay : '01'
                return moment(`${year}-${month}-${dueDay}`, "YYYY-M-DD")
            },

            budgetMonth () {
                return this.date.format('YYYY-MM') + '-01'
            },

            dueDate () {
                const date = moment(new Date())
                date.date(this.entry.dueDay)
                return date.format('Do')
                // return moment().date(this.entry.dueDay).format('Y-m-d')
            },

            getState () {
                if (this.entry.cleared) {
                    return 'cleared'
                } else if (this.entry.paid) {
                    return 'paid'
                } else if (this.entry.goal) {
                    return 'goal'
                } else {
                    return 'none'
                }
            },

            stateLabel () {
                return this.getState
            },

            rowClasses () {
                const classes = []

                if (this.active) {
                    classes.push('border bg-yellow-100 border-yellow-500')
                } else {
                    if (this.getState === 'cleared') {
                        classes.push('bg-green-50 hover:bg-green-100')
                    } else if (this.getState === 'paid') {
                        classes.push('bg-yellow-50 hover:bg-yellow-100')
                    } else if (this.getState === 'goal') {
                        classes.push('bg-red-50 hover:bg-red-100')
                    } else {
                        classes.push('bg-white hover:bg-gray-100')
                    }
                }

                return classes
            }
        },

        methods: {
            ...mapActions(['updateBudgetEntry', 'updateBankTransaction']),

            async dropped (data) {
                if (data.action === 'assign-transaction') {
                    await this.assignTransaction(data)
                }
            },

            async assignTransaction ({ action, data: { account, transaction }}) {
                const amount = transaction.amount

                if (transaction.amount < 0) {
                    // This is a deposit and can't be used
                    this.dialog = 'is-deposit'
                    return;
                }

                if (this.entry.amount !== transaction.amount) {
                    // the transaction amount does not equal the entry amount
                    this.dialog = 'not-equal'
                    return;
                }

                // Update the entry to use this transaction
                const { data: entryData } = await this.$http.updateEntry(this.budgetMonth, this.entry.id, {
                    bankTransactionId: transaction.id,
                    goal: true,
                    paid: true,
                    cleared: true
                }, 'income,group,transactions')
                await this.updateBudgetEntry(entryData)

                const { data: transactionData } = await this.$http.financialTransaction(
                    account.bankInstitutionId,
                    account.id,
                    transaction.id,
                    'entries,entries.budget'
                )
                //ToDo: Need to send
                await this.updateBankTransaction(transactionData)
            },

            updateAmount () {
                this.entry.amount = cleanNumber(this.entry.amount)
                this.saveEntry({ amount: this.entry.amount })
                this.$emit('calculate', this.entry)
            },

            updateName () {
                this.edit = null
                this.saveEntry({ name: this.entry.name })
            },

            updateStatus () {
                this.$emit('calculate', this.entry)
            },

            saveEntry (values) {
                this.$http.updateEntry(this.month, this.entry.id, { ...values }, 'income,group,transactions')
                    .then(() => {
                        this.edit = null
                    }).catch(({ error }) => {
                        console.log('error', error)
                        this.edit = null
                    })
            },

            updateDueDay (value) {
                const date = moment(value)
                this.entry.dueDay = date.format('D')
                this.saveEntry({ dueDay: this.entry.dueDay })
            },

            modify () {
                console.log('Entry::modify()', this.entry)
                this.$emit('modify', this.entry)
            }
        }
    }
</script>

<style lang="scss" scoped>
    .amount-width {
        width: 125px;
    }
<style>
