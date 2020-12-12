<template>
    <div class="border border-gray-400 rounded-md shadow-md m-1">
        <div>
            <div :class="groupClasses" class="flex border border-t-0 border-l-0 border-r-0 border-b border-gray-400 rounded-md">
                <div class="flex-none text-lg text-right px-3 py-2">
                    <ub-button @click="collapsed = !collapsed"
                               size="sm" variant="secondary"
                               :icon="collapsed ? 'chevronDoubleRight' : 'chevronDoubleDown'"
                               outline>
                    </ub-button>
                </div>
                <div @click="modifyGroup" :class="{'cursor-pointer': !this.group.unassigned}" class="flex-1 text-xl pb-2 pt-3">
                    {{ group.name }}
                </div>
                <div v-if="!this.group.unassigned" class="flex-none text-lg text-right px-3 py-2">
                    <ub-button @click="entryCreate(group)" size="sm" variant="secondary" icon="plus" outline></ub-button>
                </div>
            </div>
            <draggable handle=".entry-handle" :list="group.entries" v-bind="dragOptions" group="entries" @change="dragChanged">
                <entry-row v-if="collapsed === false" v-for="(entry, index) in group.entries" :key="`${entry}-${index}`" :active="isActive(entry)" :month="budgetDate" @calculate="calculate" @modify="modifyEntry" :entry="entry"></entry-row>
            </draggable>
            <div v-if="this.group.entries.length === 0" class="text-gray-400 px-4 py-1">
                no entries
            </div>
            <div v-else-if="collapsed === true" class="text-gray-400 px-4 py-1">
                <icon name="dotsHorizontal" class="h-5 w-5"></icon>
            </div>
        </div>
        <div v-if="!this.group.unassigned" class="text-center bg-gray-200 border border-t border-l-0 border-r-0 border-b-0 border-gray-400 rounded-md pb-1 pt-1">
            <ub-badge variant="danger" rounded outline>Expenses: {{ balances.expenses | currency }}</ub-badge>
            <ub-badge :variant="outstandingVariant(group)" rounded outline>Out Standing: {{ balances.outstanding | currency }}</ub-badge>
            <ub-badge :variant="leftOverVariant(group)" rounded outline>Left Over: {{ balances.leftOver | currency }}</ub-badge>
        </div>
    </div>
</template>

<script>
    import DueDay from '@/components/ui/DueDay'
    import Draggable from 'vuedraggable'
    import EntryRow from '@/components/budget/EntryRow'

    export default {

        components: {
            Draggable,
            EntryRow,
            DueDay
        },

        props: {
            group: {
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
                balances: [],
                draggableObject: []
            }
        },

        computed: {
            groupClasses () {
                if (this.active && !this.group.unassigned) {
                    return 'bg-yellow-100'
                }
                return ' bg-gray-200 hover:bg-gray-100'
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
                }
            }
        },

        methods: {

            isActive (entry) {
                return entry.id === this.activeRow
            },

            outstandingVariant () {
                if (this.balances.outstanding === 0) {
                    return 'success'
                } else if (this.balances.outstanding === this.balances.expenses) {
                    return 'secondary'
                }

                return 'warning'
            },

            leftOverVariant () {
                if (this.balances.leftOver === 0) {
                    return 'warning'
                } else if (this.balances.leftOver < 0) {
                    return 'danger'
                }

                return 'success'
            },

            async calculate () {
                console.log('calculate')
                this.balances = {
                    expenses: 0,
                    outstanding: 0,
                    leftOver: 0
                }
                for (let ii = 0; ii < this.group.entries.length; ii++) {
                    const entry = this.group.entries[ii]
                    this.balances.expenses = this.balances.expenses + entry.amount
                    if (!entry.cleared) {
                        this.balances.outstanding = this.balances.outstanding + entry.amount
                    }
                }
                this.balances.leftOver = this.group.amount - this.balances.expenses
            },

            async initialCollapse () {
                let completed = true
                for (let i = 0; i < this.group.entries.length; i++) {
                    if (this.group.entries[i].cleared === false) {
                        completed = false
                        break
                    }
                }
                this.collapsed = this.group.entries.length === 0 ? false : completed
            },

            entryCreate (group) {
                this.$emit('modify-entry', {
                    id: null,
                    name: '',
                    autoPay: false,
                    dueDay: 1,
                    amount: 0.00,
                    incomeId: null,
                    groupId: group.id,
                    url: ''
                })
            },

            modifyGroup () {
                if (!this.group.unassigned) {
                    this.$emit('modify-group', this.group)
                }
            },

            modifyEntry (entry) {
                this.$emit('modify-entry', entry)
            },

            /**
             * Drag and Drop functions start here
             */

            /**
             * When a drag changes this function will be executed
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
             * Execute when entry is moved to another group
             */
            async addedTo (newIndex, entry) {
                try {
                    await this.$http.updateEntry(this.budgetDate, entry.id, { groupId: this.group.id, order: newIndex })
                } catch ({ error }) {
                    console.log('error', error)
                }
            },

            /**
             * Executed when an entry is moved within its current group
             */
            async moveTo(oldIndex, newIndex, entry) {
                const ids = this.group.entries.map(e => e.id)
                try {
                    await this.$http.orderBudgetGroupEntries(this.budgetDate, this.group.id, { order: ids })
                    entry.groupId = this.group.id
                    // If entry as been added then un-collapse so results can be seen
                    this.collapsed = false
                } catch ({ error }) {
                    console.log('error', error)
                }
            }

        },

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
