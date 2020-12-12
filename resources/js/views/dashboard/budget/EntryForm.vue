<template>
    <div class="p-2">
        <div v-if="entry" class="grid grid-cols-2 gap-6">
            <div class="col-span-1">
                <ub-button v-if="entry.id" @click="showDelete = true" outline variant="danger" size="sm" icon="trash" class="float-left"></ub-button>
            </div>
            <div class="col-span-1 text-right">
                <ub-button variant="secondary" @click="cancel" size="sm" outline>Cancel</ub-button>
                <ub-button @click="save" size="sm">Save</ub-button>
            </div>
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
                <f-input label="Due Day" placeholder="1" right v-model="entry.dueDay" :right-add-on="dueDaySuffix"></f-input>
            </div>
            <div class="col-span-1">
                <f-input label="Amount" placeholder="0.00" v-model="entry.amount" left-add-on="$"></f-input>
            </div>
            <div class="col-span-2">
                <ub-select label="Income" v-model="entry.incomeId" :options="incomes" id-key="id" label-key="name" placeholder="select income">
                    <ub-select-divider></ub-select-divider>
                    <ub-select-option @click="noIncome" :value="null" label="none"/>
                </ub-select>
            </div>
            <div class="col-span-2">
                <ub-select label="Group" v-model="entry.groupId" placeholder="select group">
                    <template v-slot:selected-label>
                        {{ groupSelectedName }}
                    </template>
                    <ub-select-option v-for="(option, index) in groups"
                                      :key="`group-option-${index}`"
                                      @click="groupSelect('group', option)"
                                      :selected="isGroupSelected('other', option)"
                                      :value="option.id"
                                      :label="option.name">
                    </ub-select-option>
                    <ub-select-divider>Other</ub-select-divider>
                    <ub-select-option v-for="(option, index) in otherGroups"
                                      :key="`group-other-option-${index}`"
                                      @click="groupSelect('other', option)"
                                      :selected="isGroupSelected('other', option)"
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
        <modal v-if="showDelete" variant="danger" title="Are you sure?" confirm-label="Yes, Delete!" cancel-label="Oops! No" @confirm="deleteEntryConfirmed" @cancel="deleteEntryCanceled">
            Do you really want to delete this entry? It can't be undone.
        </modal>
    </div>
</template>

<script>
    import moment from "moment";
    import Modal from "@/components/ui/modal/Modal";

    export default {

        components: {
            Modal
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
            },
            otherGroups: {
                type: Array,
                default: () => {
                    return []
                }
            }
        },

        data () {
            return {
                showDelete: false,
                errors: [],
                groupSelectedObject: null
            }
        },

        watch: {
            entry (value) {
                this.resetGroup(value)
            }
        },

        computed: {

            groupSelected () {
                console.log(this.entry.groupId + ' === ' + this.groups.budgetGroupId)
                if (this.entry.groupId === this.groups.budgetGroupId) {
                    return this.entry.groupId
                }
                return null
            },

            selectedGroup () {
                if (this.groupSelectedObject === null) {
                    const selected = this.groups.find(g => g.id === this.entry.groupId)
                    this.groupSelectedObject = {
                        type: 'group',
                        name: selected.name || 'select group',
                        id: this.entry.groupId
                    }
                }

                return this.groupSelectedObject
            },

            groupSelectedName () {
                if (this.groupSelectedObject) {
                    return this.groupSelectedObject.name
                }
                return 'select group'
            },

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

            resetGroup (entry) {
                let id = null
                let name = 'select group'
                if (entry && entry.groupId) {
                    const group = this.groups.find(g => g.id === entry.groupId)
                    if (group) {
                        id = group.id
                        name = group.name
                    }
                }
                this.groupSelectedObject = { type: 'group', name, id}
            },

            noIncome () {
                this.entry.incomeId = null
            },

            noGroup () {
                this.groupSelectedObject = {
                    type: 'group',
                    name: 'select group',
                    id: null
                }
            },

            groupSelect (type, option) {
                this.groupSelectedObject = {
                    type: type,
                    name: option.name,
                    id: option.id
                }
            },

            isGroupSelected (type, option) {
                if (type === 'group') {
                    return option.id === this.selectedGroup.id
                }
                if (type === 'other') {
                    return option.id === this.selectedGroup.id
                }
            },

            cancel () {
                this.$emit('done', false)
            },

            async save () {
                try {
                    const saveEntry = {
                        name: this.entry.name,
                        autoPay: this.entry.autoPay,
                        dueDay: this.entry.dueDay,
                        amount: this.entry.amount,
                        incomeId: this.entry.incomeId,
                        groupId: this.entry.groupId,
                        url: this.entry.url
                    }

                    this.setGroupId(saveEntry)
                    if (this.entry.id) {
                        await this.$http.updateEntry(this.budgetMonth, this.entry.id, saveEntry)
                        this.$emit('done', true)
                    } else {
                        await this.$http.addEntry(this.budgetMonth, saveEntry)
                        this.$emit('done', true)
                    }
                } catch ({ error }) {
                    if (error.code === 422) {
                        console.log('error', error)
                    }
                }
            },

            setGroupId (entry) {
                if (this.groupSelectedObject) {
                    if (this.groupSelectedObject.type === 'other') {
                        entry.otherGroupId = this.groupSelectedObject.id
                    } else {
                        entry.groupId = this.groupSelectedObject.id
                    }
                }
            },

            async deleteEntryConfirmed () {
                await this.$http.deleteEntry(this.budgetMonth, this.entry.id)
                this.$emit('done', true)
                this.showDelete = false
            },

            deleteEntryCanceled () {
                this.showDelete = false
            }

        }
    }

</script>
