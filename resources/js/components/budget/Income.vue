<template>
    <div class="border border-gray-400 rounded-md shadow-md mb-3">
        <div>
            <div :class="incomeClasses" class="flex border border-t-0 border-l-0 border-r-0 border-b border-gray-400 rounded-md">
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
            <div v-if="collapsed === false && income.entries.length > 0">
                <draggable handle=".entry-handle" v-model="income.entries" v-bind="dragOptions" group="entries" @move="onMoveCallback" @start="startDrag" @end="endDrag">
                    <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                        <entry-row v-for="(entry, index) in income.entries" :key="`${entry}-${index}`" :active="isActive(entry)" :month="budgetDate" @calculate="calculate" @modify="modifyEntry" :entry="entry"></entry-row>
                    </transition-group>
                </draggable>
            </div>
            <div v-else class="text-gray-600 px-4 py-1">
                <icon name="dotsHorizontal" class="h-5 w-5"></icon>
            </div>
        </div>
        <div v-if="!this.income.unassigned" class="text-center bg-gray-200 border border-t border-l-0 border-r-0 border-b-0 border-gray-400 rounded-md pb-1 pt-1">
            <ub-badge variant="danger" rounded outline>Expenses: {{ balances.expenses | currency }}</ub-badge>
            <ub-badge :variant="outstandingVariant(income)" rounded outline>Out Standing: {{ balances.outstanding | currency }}</ub-badge>
            <ub-badge :variant="leftOverVariant(income)" rounded outline>Left Over: {{ balances.leftOver | currency }}</ub-badge>
        </div>
    </div>
</template>

<script>
    import DueDay from '@/components/ui/DueDay'
    import EditInPlace from '@/components/ui/form/EditInPlace'
    import Draggable from 'vuedraggable'
    import EntryRow from '@/components/budget/EntryRow'

    export default {

        components: {
            EditInPlace,
            Draggable,
            EntryRow,
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
                balances: []
            }
        },

        computed: {
            incomeClasses () {
                if (this.active && !this.income.unassigned) {
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
                console.log('calculate')
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

            updateIncomeAmount () {

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
