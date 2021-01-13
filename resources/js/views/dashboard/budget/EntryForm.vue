<template>
    <div>
        <form @submit.prevent="save">
            <budget-right-header title="Budget Entry">
                <template v-slot:left>
                    <ub-button v-if="entry.id" @click="showDelete = true" outline variant="danger" size="sm" icon="trash" class="float-left"></ub-button>
                </template>
                <template v-slot:right>
                    <ub-button variant="secondary" @click="cancel" size="sm" outline>Cancel</ub-button>
                    <ub-button type="submit" size="sm">Save</ub-button>
                </template>
            </budget-right-header>

            <div class="bg-gray-100 border border-gray-300 rounded-md shadow-md p-4 grid grid-cols-2 gap-6">
                <div v-if="errors.length >= 1" class="col-span-2">
                    {{ errors[0] }}
                </div>
                <div class="col-span-2">
                    <f-input label="Entry Name" placeholder="entry name" v-model="entry.name">
                        <template v-slot:right-add-on>
                            <div class="flex items-center">
                                <input id="dueDay" type="checkbox" v-model="entry.autoPay" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                <label for="dueDay" class="ml-2 block text-sm leading-5 text-gray-900">
                                    AutoPay
                                </label>
                            </div>
                        </template>
                    </f-input>
                </div>
                <div class="col-span-1">
                    <due-day-picker v-model="entry.dueDay" :date="budgetMonth"></due-day-picker>
                </div>
                <div class="col-span-1">
                    <f-input label="Amount" placeholder="0.00" v-model="entry.amount" left-add-on="$"></f-input>
                </div>
                <div class="col-span-2">
                    <ub-select label="Income" v-model="entry.budgetIncomeId" :options="incomes" id-key="id" label-key="name" :placeholder="noIncomeLabel">
                        <ub-select-divider></ub-select-divider>
                        <ub-select-option @click="noIncome" :value="null" label="none"/>
                    </ub-select>
                </div>
                <div class="col-span-2">
                    <ub-select label="Group" :placeholder="noGroupLabel">
                        <template v-slot:selected-label>
                            {{ entry.group && entry.group.name ? entry.group.name : noGroupLabel }}
                        </template>
                        <ub-select-option v-for="(option, index) in groups"
                                          :key="`group-option-${index}`"
                                          @click="selectGroup(option)"
                                          :selected="isGroupSelected(option)"
                                          :value="option.id"
                                          :label="option.name">
                        </ub-select-option>
                        <ub-select-divider></ub-select-divider>
                        <ub-select-option @click="noGroup" :value="null" label="none"/>
                    </ub-select>
                </div>
                <div class="col-span-2">
                    <f-input label="Url" placeholder="http://" v-model="entry.url"></f-input>
                </div>
            </div>
        </form>

        <modal v-if="showDelete" variant="danger" title="Are you sure?" confirm-label="Yes, Delete!" cancel-label="Oops! No" @confirm="deleteEntryConfirmed" @cancel="deleteEntryCanceled">
            Do you really want to delete this entry? It can't be undone.
        </modal>
    </div>
</template>

<script>
    import moment from "moment";
    import Modal from "@/components/ui/modal/Modal";
    import DueDayPicker from '@/views/dashboard/budget/DueDayPicker'
    import BudgetRightHeader from '@/views/dashboard/budget/BudgetRightHeader'
    import Budget from "@/views/dashboard/budget/Budget";
    import { budgetRemoveEntry } from '@/scripts/helpers/budgetTasks'

    export default {

        components: {
            Budget,
            Modal,
            DueDayPicker,
            BudgetRightHeader
        },

        props: {
            entry: {
                type: Object
            },
            incomes: {
                type: Array,
                default: () => {
                    return []
                }
            },
            groups: {
                type: Array,
                default: () => {
                    return []
                }
            }
        },

        data () {
            return {
                showDelete: false,
                noIncomeLabel: 'select income',
                noGroupLabel: 'select group',
                errors: [],
                groupSelectedObject: null,
                originalEntry: null
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

            dueDaySuffix () {
                const day = this.date.format('Do')
                return day.slice(-2)
            }

        },

        methods: {

            currencyFilter (value) {
                return this.$options.filters.currency(value, false)
            },

            noIncome () {
                this.entry.budgetIncomeId = null
                this.entry.income.name = this.noIncomeLabel
            },

            noGroup () {
                this.entry.groupId = null
                this.entry.budgetGroupId = null
                this.entry.group.name = this.noGroupLabel
            },

            selectGroup (option) {
                if (option.type === 'group') {
                    this.entry.groupId = option.id
                    this.entry.budgetGroupId = null
                    this.entry.group = { name: option.name }
                } else {
                    this.entry.groupId = null
                    this.entry.budgetGroupId = option.id
                    this.entry.group = { name: option.name }
                }
            },

            isGroupSelected (type, option) {
                if (option) {
                    if (type === 'group') {
                        return option.id === this.entry.groupId
                    } else {
                        return option.id === this.entry.budgetGroupId
                    }
                }
            },

            cancel () {
                // Reset this.entry to it's original state
                for (const [key, value] of Object.entries(this.originalEntry)) {
                    this.entry[key] = value
                }
                this.$emit('done', false)
            },

            /**
             * Save the entry data
             */
            async save () {
                try {
                    // Setup the entry object to be saved
                    const saveEntry = {
                        name: this.entry.name,
                        autoPay: this.entry.autoPay,
                        dueDay: this.entry.dueDay,
                        amount: this.entry.amount,
                        budgetIncomeId: this.entry.budgetIncomeId,
                        url: this.entry.url
                    }

                    // Set the budget group id or global group id if one or the other has been set
                    if (this.entry.groupId) {
                        saveEntry.groupId = this.entry.groupId
                    } else if (this.entry.budgetGroupId) {
                        saveEntry.budgetGroupId = this.entry.budgetGroupId
                    } else {
                        saveEntry.budgetGroupId = null
                    }

                    if (this.entry.id) {
                        // Update the entry
                        await this.$http.updateEntry(this.budgetMonth, this.entry.id, saveEntry)
                        this.$emit('done', false)
                    } else {
                        // Create a new entry
                        await this.$http.addEntry(this.budgetMonth, saveEntry)
                        this.$emit('done', true)
                    }
                } catch ({ error }) {
                    if (error.code === 422) {
                        console.log('error', error)
                    }
                }
            },

            /**
             * Since delete was confirmed then actually delete the entry
             */
            async deleteEntryConfirmed () {
                await this.$http.deleteEntry(this.budgetMonth, this.entry.id)
                await budgetRemoveEntry(this.entry)
                this.group = null
                this.$emit('done', false)
                this.showDelete = false
            },

            /**
             * Cancel the delete of this entry
             */
            deleteEntryCanceled () {
                this.showDelete = false
            }
        },

        mounted () {
            this.originalEntry = { ...this.entry }
        }
    }

</script>
