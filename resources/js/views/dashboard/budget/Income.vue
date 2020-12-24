<template>
    <div class="border border-gray-300 rounded-md shadow-md m-1">
        <div>
            <div :class="incomeClasses" class="flex border border-t-0 border-l-0 border-r-0 border-b border-gray-300 rounded-md rounded-b-none">
                <div class="flex-none text-lg text-right px-3 py-2">
                    <ub-button @click="collapsed = !collapsed"
                              size="sm" variant="secondary"
                              :icon="collapsed ? 'chevronDoubleRight' : 'chevronDoubleDown'"
                              outline>
                    </ub-button>
                </div>
                <div @click="modifyIncome" :class="{'cursor-pointer': !this.income.unassigned}" class="flex-1 text-xl pb-2 pt-3">
                    {{ income.name }}
                    <span v-if="!this.income.unassigned" class="text-xs text-gray-600">Due: <due-day :value="income.dueDay"></due-day></span>
                </div>
                <div v-if="!this.income.unassigned" @click="modifyIncome" :class="{'cursor-pointer': !this.income.unassigned}" class="flex-none text-xl text-right px-3 pb-2 pt-3" style="width: 125px;">
                    {{ income.amount | currency }}
                </div>
                <div v-if="!this.income.unassigned" class="flex-none text-lg text-right px-3 py-2">
                    <ub-button @click="entryCreate(income)" size="sm" variant="secondary" icon="plus" outline></ub-button>
                </div>
            </div>
            <draggable handle=".entry-handle" :list="income.entries" v-bind="dragOptions" group="entries" @change="dragChanged">
                <entry-row v-if="collapsed === false" v-for="(entry, index) in income.entries" :key="`${entry}-${index}`" :active="isActive(entry)" :month="budgetDate" @calculate="calculate" @modify="modifyEntry" :entry="entry"></entry-row>
            </draggable>
            <div v-if="this.income.entries.length === 0" class="text-gray-400 px-4 py-1">
                no entries
            </div>
            <div v-else-if="collapsed === true" class="text-gray-400 px-4 py-1">
                <icon name="dotsHorizontal"></icon>
            </div>
        </div>
        <div v-if="!this.income.unassigned" class="text-center bg-gray-100 border border-t border-l-0 border-r-0 border-b-0 border-gray-300 rounded-md rounded-t-none pb-1 pt-1">
            <ub-badge variant="danger" rounded outline>Expenses: {{ balances.expenses | currency }}</ub-badge>
            <ub-badge :variant="outstandingVariant(income)" rounded outline>Out Standing: {{ balances.outstanding | currency }}</ub-badge>
            <ub-badge :variant="leftOverVariant(income)" rounded outline>Left Over: {{ balances.leftOver | currency }}</ub-badge>
        </div>
        <modal v-if="changeDueDay"
               variant="info"
               title="Modify Due Day"
               confirm-label="Make it so"
               cancel-label="Oops! No"
               @confirm="entryMoveConfirm"
               @cancel="entryMoveCanceled">

            <entry-row :month="budgetDate" :entry="changeDueDay" hide-progress></entry-row>

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
    import EntryRow from '@/components/budget/EntryRow'
    import DueDayPicker from '@/views/dashboard/budget/DueDayPicker'
    import Modal from "@/components/ui/modal/Modal";
    import moment from "moment";

    export default {

        components: {
            Draggable,
            EntryRow,
            DueDayPicker,
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
                balances: [],
                changeDueDay: null
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
            }
        },

        methods: {

            isActive (entry) {
                return entry.id === this.activeRow
            },

            outstandingVariant (income) {
                if (this.balances.outstanding === 0) {
                    return 'success'
                } else if (this.balances.outstanding === this.balances.expenses) {
                    return 'secondary'
                }

                return 'warning'
            },

            leftOverVariant (income) {
                if (this.balances.leftOver === 0) {
                    return 'warning'
                } else if (this.balances.leftOver < 0) {
                    return 'danger'
                }

                return 'success'
            },

            async calculate () {
                this.balances = {
                    expenses: 0,
                    outstanding: 0,
                    leftOver: 0
                }
                for (let ii = 0; ii < this.income.entries.length; ii++) {
                    const entry = this.income.entries[ii]
                    this.balances.expenses = this.balances.expenses + entry.amount
                    if (!entry.cleared) {
                        this.balances.outstanding = this.balances.outstanding + entry.amount
                    }
                }
                this.balances.leftOver = this.income.amount - this.balances.expenses
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
                    incomeId: income.id,
                    groupId: null,
                    url: ''
                })
            },

            modifyIncome () {
                if (!this.income.unassigned) {
                    this.$emit('modify-income', this.income)
                }
            },

            modifyEntry (entry) {
                this.$emit('modify-entry', entry)
            },

            /**
             * Due Day change and modal methods below
             */

            /**
             * Triggered when an entry row drag is dropped
             */
            dragChanged (evt) {
                console.log('evt', evt)
                if (evt.added) {
                    this.addedTo(evt.added.newIndex, evt.added.element)
                    console.log(`${evt.added.element.name} was added to the group`)
                } else if (evt.moved) {
                    this.moveTo(evt.moved.oldIndex, evt.moved.newIndex, evt.moved.element)
                }
            },

            /**
             * Resort the income's entries by dueDay
             */
            orderEntriesByDueDay () {
                console.log('orderEntriesByDueDay', this.income.entries);
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
                    await this.$http.updateEntry(this.budgetDate, entry.id, { incomeId: this.income.id, order: newIndex })
                    // Update the entries income id here so we don't have to reload the entire budget
                    entry.incomeId = this.income.id
                    this.orderEntriesByDueDay()
                    // If entry as been added then un-collapse so results can be seen
                    this.collapsed = false
                } catch ({ error }) {
                    console.log('addedTo error', error)
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
                    console.log('entryMoveConfirm error', error)
                }
            },

            /**
             * Move the entry back to it's original location
             */
            entryMoveCanceled () {
                const entry = this.income.entries.splice(this.changeDueDay.newIndex, 1)
                this.income.entries.splice(this.changeDueDay.oldIndex, 0, entry[0])
                this.changeDueDay = null
            }

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
