<template>
    <div class="border border-gray-300 rounded-md shadow-md mx-1 mb-6">
        <div>
            <drop-zone @dropped="dropped" :opacity="0.2" class="sticky top-0">
                <income-header :income="income" :collapsed="collapsed" @collapsed="toggleCollapsed" @modify-income="modifyIncome" @create-entry="createEntry"></income-header>
            </drop-zone>
            <div v-if="this.income.entries.length === 0" class="text-gray-400 text-gray-50 text-sm px-4 py-1">
                no entries
            </div>
            <div v-else-if="collapsed === 3" class="bg-gray-50 text-gray-400 text-sm px-4 py-1">
                all hidden
            </div>
            <div v-else-if="collapsed === 2" class="flex bg-gray-50 text-gray-400 text-sm px-4 py-1">
                cleared hidden
            </div>
            <draggable handle=".entry-handle" :list="income.entries" v-bind="dragOptions" @change="dragChanged">
                <entry v-for="(entry, index) in income.entries" v-if="entryVisibility(entry)" :key="`entry-${index}`" :active="isActive(entry)" :month="budgetDate" @calculate="calculate" @modify="modifyEntry" :entry="entry"></entry>
            </draggable>
            <div v-if="!this.income.unassigned" class="bg-gray-100 border border-t border-l-0 border-r-0 border-b-0 border-gray-300 rounded-md rounded-t-none">
                <budget-balances :balances="balances" class="border border-b rounded-md rounded-t-none"></budget-balances>
            </div>
        </div>
        <modal v-if="dialog === 'is-debit'" title="Amount is a Debit" variant="warning" hide-cancel confirm-label="Okay" @confirm="dialog = null">
            The transaction is a debit and can't be used to link an income.
        </modal>
        <modal v-if="dialog === 'not-equal'" title="Amounts not Equal" variant="warning" confirm-label="Yes, Change Amount" cancel-label="No, Cancel" @confirm="droppedConfirmIncomeLink" @cancel="dialog = null">
            The deposit transaction amount and income amount are not the same. Do you want to change the income amount to match the deposit amount.
        </modal>
        <modal v-if="changeDueDay"
               variant="info"
               title="Modify Due Day"
               confirm-label="Done"
               hide-cancel
               @confirm="entryMoveConfirm">

            <entry :month="budgetDate" :entry="changeDueDay" hide-progress></entry>

            <p class="p-4 pb-0">
                <strong>Note:</strong> Income entries are ordered by due day, so select the new due day for this entry.
            </p>

            <div class="flex">
                <div class="flex-1">
                    <!-- This item will grow or shrink as needed -->
                </div>
                <div class="flex-shrink-0 p-6">
                    <f-datepicker :value="pickerDueDate"
                                  inline
                                  format="dd"
                                  class="mx-auto"
                                  :min-date="pickerMinDate"
                                  :max-date="pickerMaxDate"
                                  @input="pickerUpdateDueDay">
                    </f-datepicker>
                </div>
                <div class="flex-1">
                    <!-- This item will grow or shrink as needed -->
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    import IncomeHeader from '@/views/dashboard/budget/IncomeHeader'
    import DueDay from '@/components/ui/DueDay'
    import Draggable from 'vuedraggable'
    import DropZone from '@/components/ui/dragdrop/DropZone';
    import Entry from '@/views/dashboard/budget/Entry'
    import DueDayPicker from '@/views/dashboard/budget/DueDayPicker'
    import BudgetBalances from '@/views/dashboard/budget/BudgetBalances'
    import Modal from "@/components/ui/modal/Modal";
    import moment from "moment";
    import { calculateBalances } from '@/scripts/helpers/utils'
    import { mapActions } from 'vuex'

    export default {

        components: {
            IncomeHeader,
            Draggable,
            DropZone,
            Entry,
            DueDayPicker,
            BudgetBalances,
            Modal,
            DueDay
        },

        props: {
            income: {
                type: Object
            },
            active: {
                type: Boolean,
                default: false
            },
            activeRow: {
                type: [String, Number],
                default: null
            }
        },

        data () {
            return {
                drag: false,
                dialog: null,
                collapsed: 2,
                dragging: null,
                changeDueDay: null,
                balances: null,
                transaction: null
            }
        },

        computed: {

            pickerDueDate () {
                if (!this.changeDueDay.dueDay) {
                    this.changeDueDay.dueDay = 1
                }
                return moment().date(this.changeDueDay.dueDay).toDate()
            },

            pickerMinDate () {
                return moment().date(this.changeDueDay.dueDay).startOf('month').toDate()
            },

            pickerMaxDate () {
                return moment().date(this.changeDueDay.dueDay).endOf('month').toDate()
            },

            incomeClasses () {
                if (this.active && !this.income.unassigned) {
                    return 'bg-yellow-100'
                }
                return ' bg-gray-100 hover:bg-gray-200'
            },

            budgetDate () {
                return `${this.$route.params.year}-${this.$route.params.month}-01`
            },

            dragOptions() {
                return {
                    animation: 200,
                    group: "entries",
                    disabled: false,
                    ghostClass: "ghost"
                };
            },
        },

        methods: {
            ...mapActions(['updateBudgetIncome', 'updateBankTransaction']),

            async dropped (data) {
                if (data.action === 'assign-transaction') {
                    this.transaction = data.data.transaction
                    const transactionAmount = this.swapAmount(data.data.transaction.amount)
                    const incomeAmount = this.income.amount
                    if (transactionAmount < 0) {
                        this.dialog = 'is-debit'
                        this.transaction = null
                    } else if (incomeAmount !== transactionAmount) {
                        this.dialog = 'not-equal'
                    } else {
                        await this.updateIncomeWithTransaction(this.transaction)
                        this.transaction = null
                        this.dialog = null
                    }
                }
            },

            async droppedConfirmIncomeLink () {
                const amount = Math.abs(this.transaction.amount)
                await this.updateIncomeWithTransaction(this.transaction, amount)
                this.transaction = null
                this.dialog = null
            },

            async updateIncomeWithTransaction (transaction, amount = null) {
                try {
                    const update = { bankTransactionId: this.transaction.id }
                    if (amount !== null) update.amount = amount
                    const { data: income } = await this.$http.updateIncome(this.budgetDate, this.income.id, update, 'budget,transaction')
                    console.log('update income', income)
                    this.updateBudgetIncome({
                        id: income.id,
                        amount: income.amount,
                        transaction: transaction
                    })
                    transaction.income = income
                    this.updateBankTransaction(transaction)
                } catch ({ error }) {
                    console.log('error', error)
                }
            },

            swapAmount(amount) {
                if (amount < 0) {
                    return Math.abs(amount)
                } else if (amount > 0) {
                    return -Math.abs(amount)
                }
                return 0
            },

            isActive (entry) {
                return entry.id === this.activeRow
            },

            async calculate () {
                this.balances = calculateBalances(this.income.entries, this.income.amount)
            },

            async initialCollapse () {
                let cleared = false
                for (let i = 0; i < this.income.entries.length; i++) {
                    if (this.income.entries[i].cleared === true) {
                        cleared++
                    }
                }

                if (cleared === this.income.entries.length) {
                    this.collapsed = 3
                } else if (cleared >= 1) {
                    this.collapsed = 2
                } else {
                    this.collapsed = 1
                }
            },

            createEntry () {
                this.$emit('modify-entry', {
                    id: null,
                    name: '',
                    autoPay: false,
                    dueDay: 1,
                    amount: 0.00,
                    budgetIncomeId: this.income.id,
                    budgetGroupId: null,
                    url: ''
                })
            },

            modifyIncome () {
                if (!this.income.unassigned) {
                    this.$emit('modify-income', this.income)
                }
            },

            async modifyEntry (entry) {
                try {
                    this.$emit('modify-entry', entry)
                } catch (error) {
                    console.log('error', error)
                }
            },

            /**
             * Due Day change and modal methods below
             */

            /**
             * Triggered when an entry row drag is dropped
             */
            dragChanged (evt) {
                console.log('evt: entry', evt)
                if (evt.added) {
                    this.addedTo(evt.added.newIndex, evt.added.element)
                } else if (evt.moved) {
                    this.moveTo(evt.moved.oldIndex, evt.moved.newIndex, evt.moved.element)
                }
            },

            /**
             * Resort the income's entries by dueDay
             */
            orderEntriesByDueDay () {
                this.income.entries.sort((a, b) => {
                    if (a.dueDay === b.dueDay) {
                        // Name is only important when dueDays are the same
                        return a.name.toLowerCase().localeCompare(b.name.toLowerCase())
                    }
                    return a.dueDay > b.dueDay ? 1 : -1;
                })
            },

            /**
             * Update the entry's incomeId value and set it's new position
             * based on the new income group's dueDays
             */
            async addedTo (newIndex, entry) {
                try {
                    await this.$http.updateEntry(this.budgetDate, entry.id, { budgetIncomeId: this.income.id, order: newIndex })
                    // Update the entries income id here so we don't have to reload the entire budget
                    entry.incomeId = this.income.id
                    this.orderEntriesByDueDay()
                    // If entry as been added then un-collapse so results can be seen
                    this.collapsed = 1
                } catch ({ error }) {
                    console.log('error', error)
                }
            },

            /**
             * Triggered if an entry is moved within the same income group
             * Set the old and new index and open due day picker dialog
             */
            async moveTo(oldIndex, newIndex, entry) {
                entry.oldIndex = oldIndex
                entry.newIndex = newIndex
                this.changeDueDay = entry
            },

            /**
             * Set the changeDueDay's new dueDay value from the dialog picker
             */
            pickerUpdateDueDay (value) {
                value = moment(value).format('D')
                // Remove leading 0 if it exists
                this.changeDueDay.dueDay = value
            },

            /**
             * Update the entry's new dueDay and close the dueDay dialog
             */
            async entryMoveConfirm () {
                try {
                    // Update the entry's dueDay in the api
                    await this.$http.updateEntry(this.budgetDate, this.changeDueDay.id, { dueDay: this.changeDueDay.dueDay })
                    // Set the dueDay on the entry so we dont' have to reload the entire budget
                    this.income.entries.find(e => e.id === this.changeDueDay.id).dueDay = this.changeDueDay.dueDay
                    // Sort the income's entries
                    this.orderEntriesByDueDay()
                    // Close the dialog
                    this.changeDueDay = null
                } catch (error) {
                    console.log('error', error)
                }
            },

            toggleCollapsed (collapsed) {
                if (collapsed) {
                    this.collapsed = collapsed
                } else {
                    this.collapsed++
                    if (this.collapsed > 3) {
                        this.collapsed = 1
                    }
                }
            },

            entryVisibility (entry) {
                if (this.collapsed === 1) {
                    return true
                } else if (this.collapsed === 2) {
                    return !entry.cleared
                }

                return false;
            }

            /**
             * Move the entry back to it's original location
             */
            // entryMoveCanceled () {
            //     const entry = this.income.entries.splice(this.changeDueDay.newIndex, 1)
            //     this.income.entries.splice(this.changeDueDay.oldIndex, 0, entry[0])
            //     this.changeDueDay = null
            // }

        },

        /**
         * Update the income state when loaded
         * Calculate initial values
         * Set initial collapse state
         */
        async mounted () {
            await this.calculate()
            await this.initialCollapse()
        }

    }
</script>

<style lang="scss" scoped>
    .ghost {
        visibility: hidden;
    }
</style>
