<template>
    <div class="md:flex lg:items-center lg:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-700 sm:text-3xl sm:truncate">
                {{ monthName }}
                <span class="text-sm text-gray-600">{{ year }}</span>
            </h2>
            <div class="hidden sm:inline mt-1 pt-2 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-2">
                <badge variant="success" outline small>
                    Income: {{ income | currency }}
                </badge>
                <badge variant="danger" outline small>
                    Expenses: {{ expenses | currency }}
                </badge>
                <badge variant="success" outline small>
                    Left Over: {{ leftOver | currency }}
                </badge>
            </div>
            <div class="inline sm:hidden mt-1 pt-2 flex flex-row flex-wrap text-center mt-0 space-x-2">
                <badge variant="success" outline small class="flex-1 align-center">
                    Income: {{ income | currency }}
                </badge>
                <badge variant="danger" outline small class="flex-1">
                    Expenses: {{ expenses | currency }}
                </badge>
                <badge variant="success" outline small class="flex-1">
                    Left Over: {{ leftOver | currency }}
                </badge>
            </div>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4">
            <f-button @click="previous" outline block icon="chevronDoubleLeft" class="mr-1"></f-button>
            <f-button @click="today" outline block icon="calendar"></f-button>
            <f-button @click="next" outline block icon="chevronDoubleRight" class="ml-1"></f-button>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'

    export default {

        props: {
            income: {
                type: Number,
                default: 0
            },
            expenses: {
                type: Number,
                default: 0
            },
            leftOver: {
                type: Number,
                default: 0
            }
        },

        computed: {
            monthName () {
                return moment(this.budgetDate).format('MMMM')
            },
            month () {
                return this.$route.params.month
            },
            year () {
                return this.$route.params.year
            },
            budgetDate () {
                return `${this.year}-${this.month}-01`
            }
        },

        methods: {
            previous () {
                const date = moment(this.budgetDate).subtract(1, 'month')
                this.redirect(date)
            },
            today () {
                const date = moment()
                this.redirect(date)
            },
            next () {
                const date = moment(this.budgetDate).add(1, 'month')
                this.redirect(date)
            },

            redirect (date) {
                const year = date.format('YYYY')
                const month = date.format('MM')
                this.$router.push({ name: 'budget', params: { year, month }})
            }
        }

    }

</script>
