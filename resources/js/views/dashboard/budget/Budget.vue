<template>
    <layout>
        <template v-slot:header>
            <div class="float-left">
                Budget
            </div>
            <div class="float-right">
                <button>Testing</button>
            </div>
            <div class="clearfix"></div>
        </template>

        <income v-if="view.state === null" v-for="(income, index) in incomes" :key="`income-${index}`" :income="income">
            <draggable v-model="income.entries" handle=".entry-handle" v-bind="dragOptions" group="entries" @start="startDrag" @end="endDrag">
                <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                    <entry-row v-for="(entry, index) in income.entries" :key="`${entry}-${index}`" :month="budget.month" @dialog="entryDialog" :entry="entry"></entry-row>
                </transition-group>
            </draggable>
        </income>

        <entry-form v-if="view.state === 'entry-form'" :entry="view.data"></entry-form>

    </layout>
</template>

<script>
    import Layout from "@/components/layouts/Layout"
    import EntryForm from '@/components/budget/EntryForm'
    import EntryRow from '@/components/budget/EntryRow'
    import Draggable from 'vuedraggable'
    import Income from '@/components/budget/Income'
    import moment from 'moment'

    export default {

        components: {
            Layout,
            EntryForm,
            EntryRow,
            Draggable,
            Income
        },

        data () {
            return {
                budget: {},
                drag: false,
                view: {
                    state: 'entry-form',
                    data: {}
                }
            }
        },

        computed: {
            budgetDate () {
                const year = this.$route.params.year
                const month = this.$route.params.month
                return moment(`${year}-${month}-01`, 'YYYY-MM-DD').format('YYYY-MM-DD')
            },
            incomes () {
                return this.budget.incomes || []
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

        watch: {
            $route () {
                this.loadBudget()
            }
        },

        methods: {
            startDrag (value) {
                this.drag = true
            },
            endDrag (value, value2) {
                this.drag = false
                console.log('value', value)
                console.log('value2', value2)
            },
            refresh () {
                this.loadBudget()
            },
            loadBudget () {
                this.redirectIfNoDate()
                this.budget = {}
                this.$store.commit('loading', true)
                this.$http.getBudget(this.budgetDate, 'incomes,incomes.entries,incomes.entries.group')
                    .then(({ data }) => {
                        this.budget = data
                        console.log('data', data)
                        this.$store.commit('loading', false)
                    }).catch(({ error }) => {
                        this.$store.commit('loading', false)
                        if (error.code === 404) {
                            this.view = 'addBudget'
                        } else {
                            this.error = error
                            this.view = 'error'
                        }
                    })
            },
            redirectIfNoDate () {
                let year = this.$route.params.year
                let month = this.$route.params.month
                if (!year || !month) {
                    let year = moment().format('YYYY')
                    let month = moment().format('MM')
                    this.$router.push({ name: 'budget', params: { year, month }})
                }
            },
            entryDialog (entry) {
                this.view = {
                    state: 'entry-form',
                    data: entry
                }
            }
        },

        mounted () {
            this.loadBudget()
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
