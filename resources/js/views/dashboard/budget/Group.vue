<template>
    <div class="border border-gray-400 rounded-md shadow-md mb-3">
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
                <div v-if="!this.group.unassigned" @click="modifyGroup" :class="{'cursor-pointer': !this.group.unassigned}" class="flex-none text-xl text-right px-3 pb-2 pt-3" style="width: 125px;">
                    {{ group.amount | currency }}
                </div>
                <div v-if="!this.group.unassigned" class="flex-none text-lg text-right px-3 py-2">
                    <ub-button @click="entryCreate(group)" size="sm" variant="secondary" icon="plus" outline></ub-button>
                </div>
            </div>
            <div v-if="collapsed === false && group.entries.length > 0">
                <draggable handle=".entry-handle" v-model="group.entries" v-bind="dragOptions" group="entries" @move="onMoveCallback" @start="startDrag" @end="endDrag">
                    <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                        <entry-row v-for="(entry, index) in group.entries" :key="`${entry}-${index}`" :active="isActive(entry)" :month="budgetDate" @calculate="calculate" @modify="modifyEntry" :entry="entry"></entry-row>
                    </transition-group>
                </draggable>
            </div>
            <div v-else class="text-gray-600 px-4 py-1">
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
                balances: []
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
                };
            }
        },

        methods: {

            startDrag (value) {
                console.log('startDrag', value)
                this.drag = true
            },

            endDrag (value) {
                console.log('endDrag', value)
                this.drag = false
            },

            onMoveCallback (evt, originalEvent) {
                console.log('onMoveCallback', evt, originalEvent)
                // ...
                // return false; — for cancel
            },

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
            }

        },

        async mounted () {
            await this.calculate()
            await this.initialCollapse()
        }

    }

</script>
