<template>
    <div class="border border-gray-300 rounded-md shadow-md m-1">
        <div>
            <div :class="incomeClasses" class="sticky top-0 flex border border-t-0 border-l-0 border-r-0 border-b border-gray-300 rounded-md rounded-b-none">
                <!-- Collapse Button -->
                <div class="flex-none text-lg text-right px-3 py-2">
                    <ub-button @click="collapsed = !collapsed"
                              size="sm" variant="secondary"
                              :icon="collapsed ? 'chevronDoubleRight' : 'chevronDoubleDown'"
                              outline>
                    </ub-button>
                </div>
                <!-- Income Title -->
                <div @click="modifyIncome" :class="{'cursor-pointer': !this.income.unassigned}" class="flex-1 text-xl pb-2 pt-3">
                    {{ income.name }}
                    <span v-if="!this.income.unassigned" class="text-xs text-gray-600">Due: <due-day :value="income.dueDay"></due-day></span>
                </div>
                <!-- Income Amount -->
                <div v-if="!this.income.unassigned" @click="modifyIncome" :class="{'cursor-pointer': !this.income.unassigned}" class="flex-none text-xl text-right px-3 pb-2 pt-3" style="width: 125px;">
                    {{ income.amount | currency }}
                </div>
                <!-- Create Entry Button -->
                <div v-if="!this.income.unassigned" class="flex-none text-lg text-right px-3 py-2">
                    <ub-button @click="entryCreate(income)" size="sm" variant="secondary" icon="plus" outline></ub-button>
                </div>
            </div>
            <draggable handle=".entry-handle" :list="income.entries" v-bind="dragOptions" group="entries" @change="dragChanged">
                <entry v-for="(entry, index) in income.entries" v-if="entry && collapsed === false" :key="`${entry}-${index}`" :active="isActive(entry)" :month="budgetDate" @calculate="calculate" @modify="modifyEntry" :entry="entry"></entry>
            </draggable>
            <div v-if="this.income.entries.length === 0" class="text-gray-400 px-4 py-1">
                no entries
            </div>
            <div v-else-if="collapsed === true" class="text-gray-400 px-4 py-1">
                <icon name="dotsHorizontal"></icon>
            </div>
            <div v-if="!this.income.unassigned" class="bg-gray-100 border border-t border-l-0 border-r-0 border-b-0 border-gray-300 rounded-md rounded-t-none">
                <budget-balances :balances="balances" class="border border-b rounded-md rounded-t-none"></budget-balances>
            </div>
        </div>
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
    import DueDay from '@/components/ui/DueDay'
    import Draggable from 'vuedraggable'
    import Entry from '@/views/dashboard/budget/Entry'
    import DueDayPicker from '@/views/dashboard/budget/DueDayPicker'
    import BudgetBalances from '@/views/dashboard/budget/BudgetBalances'
    import Modal from "@/components/ui/modal/Modal";
    import moment from "moment";
    import { calculateBalances } from '@/scripts/helpers/utils'

    export default {

        components: {
            Draggable,
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
                collapsed: false,
                dragging: null,
                changeDueDay: null,
                balances: null
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
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            },
        },

        methods: {

            isActive (entry) {
                return entry.id === this.activeRow
            },

            async calculate () {
                this.balances = calculateBalances(this.income.entries, this.income.amount)
            },

            async initialCollapse () {
                let completed = true
                for (let i = 0; i < this.income.entries.length; i++) {
                    if (this.income.entries[i].cleared === false) {
                        completed = false
                        break
                    }
                }
                this.collapsed = this.income.entries.length === 0 ? false : completed
            },

            entryCreate (income) {
                this.$emit('modify-entry', {
                    id: null,
                    name: '',
                    autoPay: false,
                    dueDay: 1,
                    amount: 0.00,
                    budgetIncomeId: income.id,
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
                    this.collapsed = false
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
