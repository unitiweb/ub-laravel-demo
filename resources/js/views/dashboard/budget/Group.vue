<template>
    <div class="border border-gray-300 rounded-md shadow-md m-1 mb-6">
        <div>
            <div :class="groupClasses" class="sticky top-0 flex border border-t-0 border-l-0 border-r-0 border-b border-gray-300 rounded-md rounded-b-none">
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
                <entry v-for="(entry, index) in group.entries" v-if="entry && collapsed === false" :key="`${entry}-${index}`" :active="isActive(entry)" :month="budgetDate" @calculate="calculate" @modify="modifyEntry" :entry="entry"></entry>
            </draggable>
            <div v-if="this.group.entries.length === 0" class="text-gray-400 px-4 py-1">
                no entries
            </div>
            <div v-else-if="collapsed === true" class="text-gray-400 px-4 py-1">
                <icon name="dotsHorizontal"></icon>
            </div>
        </div>
        <div v-if="!this.group.unassigned" class="text-center bg-gray-200 border border-t border-l-0 border-r-0 border-b-0 border-gray-300 rounded-md rounded-t-none pb-1 pt-1">
            <budget-balances :balances="balances" class="border border-b"></budget-balances>
        </div>
    </div>
</template>

<script>
    import DueDay from '@/components/ui/DueDay'
    import Draggable from 'vuedraggable'
    import Entry from '@/views/dashboard/budget/Entry'
    import BudgetBalances from '@/views/dashboard/budget/BudgetBalances'
    import { calculateBalances } from "@/scripts/helpers/utils";

    export default {

        components: {
            Draggable,
            Entry,
            DueDay,
            BudgetBalances
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
                balances: {},
                draggableObject: []
            }
        },

        computed: {
            groupClasses () {
                if (this.active && !this.group.unassigned) {
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
                }
            }
        },

        methods: {

            isActive (entry) {
                return entry.id === this.activeRow
            },

            async calculate () {
                this.balances = calculateBalances(this.group.entries, this.group.amount)
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
                    budgetIncomeId: null,
                    budgetGroupId: group.id,
                    url: ''
                })
            },

            modifyGroup () {
                if (!this.group.unassigned) {
                    this.$emit('modify-group', this.group)
                }
            },

            async modifyEntry (entry) {
                try {
                    const { data } = await this.$http.getEntry(this.budgetDate, entry.id)
                    this.$emit('modify-entry', data)
                } catch (error) {
                    console.log('error', error)
                }
            },

            /**
             * Drag and Drop functions start here
             */

            /**
             * When a drag changes this function will be executed
             */
            dragChanged (evt) {
                if (evt.added) {
                    this.addedTo(evt.added.newIndex, evt.added.element)
                } else if (evt.moved) {
                    this.moveTo(evt.moved.oldIndex, evt.moved.newIndex, evt.moved.element)
                }
            },

            /**
             * Execute when entry is moved to another group
             */
            async addedTo (newIndex, entry) {
                try {
                    await this.$http.updateEntry(this.budgetDate, entry.id, { budgetGroupId: this.group.id, order: (newIndex + 1) })
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
                    await this.$http.updateEntry(this.budgetDate, entry.id, { order: (newIndex + 1) })
                    // await this.$http.updateEntry(this.budgetDate, entry.id, this.group.id, { order: ids })
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
