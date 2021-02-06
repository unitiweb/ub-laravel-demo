<template>
    <div>
        <div :class="incomeClasses" class="block md:hidden border border-t-0 border-l-0 border-r-0 border-b border-gray-300 rounded-md rounded-b-none">
            <!-- Medium Income Header-->
            <div class="flex">
                <div class="flex-none text-lg text-right px-3 py-2">
                    <ub-button @click="setCollapse"
                               size="sm" variant="secondary"
                               :icon="collapsedIcon"
                               outline>
                    </ub-button>
                </div>
                <div class="flex-1"></div>
                <div v-if="!this.income.unassigned" class="flex-none text-lg text-right px-3 py-2">
                    <ub-button @click="createEntry" size="sm" variant="secondary" icon="plus" outline></ub-button>
                </div>
            </div>
            <div class="flex">
                <!-- Income Title -->
                <div @click="modifyIncome" :class="{'cursor-pointer': !this.income.unassigned}" class="flex-none text-xl pb-2 pl-2">
                    {{ income.name }}
                    <span v-if="!this.income.unassigned" class="text-xs text-gray-600">Due: <due-day :value="income.dueDay"></due-day></span>
                </div>
                <div class="flex-1"></div>
                <div v-if="!this.income.unassigned" @click="modifyIncome" :class="{'cursor-pointer': !this.income.unassigned}" class="flex-none text-xl text-right px-3 pb-2" style="width: 125px;">
                    {{ income.amount | currency }}
                </div>
            </div>
        </div>
        <div :class="incomeClasses" class="sticky top-0 hidden md:flex border border-t-0 border-l-0 border-r-0 border-b border-gray-300 rounded-md rounded-b-none">
            <!-- Medium Income Header-->
            <div class="flex-none text-lg text-right px-3 py-2">
                <ub-button @click="setCollapse"
                           size="sm" variant="secondary"
                           :icon="collapsedIcon"
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
                <ub-button @click="createEntry" size="sm" variant="secondary" icon="plus" outline></ub-button>
            </div>
        </div>
    </div>
</template>

<script>
    import DueDay from '@/components/ui/DueDay'

    export default {

        components: {
            DueDay
        },

        props: {
            income: {
                type: Object
            },
            collapsed: {
                type: Number,
                default: 3
            }
        },

        computed: {
            incomeClasses () {
                const classes = []
                if (this.active && !this.income.unassigned) {
                    classes.push('bg-yellow-100 hover:bg-yellow-200')
                } else if (this.income.transaction) {
                    classes.push('bg-green-50 hover:bg-green-100')
                } else {
                    classes.push('bg-gray-100 hover:bg-gray-200')
                }
                return classes
            },

            collapsedIcon () {
                if (this.collapsed === 1) {
                    return 'chevronDoubleRight'
                } else if (this.collapsed === 2) {
                    return 'chevronDoubleDown'
                }

                return 'chevronDown'
            }
        },

        methods: {
            setCollapse () {
                this.$emit('collapsed')
            },

            modifyIncome () {
                if (!this.income.unassigned) {
                    this.$emit('modify-income')
                }
            },

            createEntry () {
                this.$emit('create-entry')
            }
        }

    }

</script>
