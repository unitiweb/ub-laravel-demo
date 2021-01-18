<template>
    <div :class="rowClasses" class="flex border-b rounded-md rounded-b-none cursor-pointer m-1 pb-1">
        <div class="flex-none border-r px-1 pt-4">
            <icon name="menu" fill size="4" class="entry-handle cursor-move text-gray-300 hover:text-gray-700"></icon>
        </div>
        <div @click="modify" class="flex-1 px-2 pt-1">
            {{ entry.name }}
            <div class="text-xs text-gray-600">
                Due Day: {{ dueDate }}
            </div>
        </div>
        <div @click="modify" class="flex-none">
            <div class="text-right text-lg amount-width px-3 pt-3">
                {{ entry.amount | currency }}
            </div>
        </div>
        <div v-if="!hideProgress" class="flex-none rounded-md pt-2 pr-2">
            <entry-progress v-model="entry" :month="this.month" @updated="updateStatus"></entry-progress>
        </div>
    </div>
</template>

<script>
    import Currency from '@/components/ui/Currency'
    import DueDay from '@/components/ui/DueDay'
    import EntryProgress from '@/views/dashboard/budget/EntryProgress2'
    import { cleanNumber } from '@/scripts/helpers/utils'
    import EditInPlace from '@/components/ui/form/EditInPlace'
    import moment from 'moment'

    export default {

        components: {
            Currency,
            DueDay,
            EntryProgress,
            EditInPlace
        },

        props: {
            month: {
                type: String
            },
            entry: {
                type: Object
            },
            active: {
                type: Boolean,
                default: false
            },
            hideProgress: {
                type: Boolean,
                default: false
            }
        },

        data () {
            return {
                edit: null
            }
        },

        computed: {
            dueDate () {
                const date = moment(new Date())
                date.date(this.entry.dueDay)
                return date.format('Do')
                // return moment().date(this.entry.dueDay).format('Y-m-d')
            },

            getState () {
                if (this.entry.cleared) {
                    return 'cleared'
                } else if (this.entry.paid) {
                    return 'paid'
                } else if (this.entry.goal) {
                    return 'goal'
                } else {
                    return 'none'
                }
            },

            rowClasses () {
                const classes = []

                if (this.active) {
                    classes.push('border bg-yellow-100 border-yellow-500')
                } else {
                    if (this.getState === 'cleared') {
                        classes.push('bg-green-50 hover:bg-green-100')
                    } else if (this.getState === 'paid') {
                        classes.push('bg-yellow-50 hover:bg-yellow-100')
                    } else if (this.getState === 'goal') {
                        classes.push('bg-red-50 hover:bg-red-100')
                    } else {
                        classes.push('bg-white hover:bg-gray-100')
                    }
                }

                return classes
            }
        },

        methods: {
            updateAmount () {
                this.entry.amount = cleanNumber(this.entry.amount)
                this.saveEntry({ amount: this.entry.amount })
                this.$emit('calculate', this.entry)
            },

            updateName () {
                this.edit = null
                this.saveEntry({ name: this.entry.name })
            },

            updateStatus (entry) {
                this.$emit('calculate', entry)
            },

            saveEntry (values) {
                this.$http.updateEntry(this.month, this.entry.id, { ...values })
                    .then(({ data }) => {
                        this.edit = null
                    }).catch(({ error }) => {
                        console.log('error', error)
                        this.edit = null
                    })
            },

            updateDueDay (value) {
                const date = moment(value)
                this.entry.dueDay = date.format('D')
                this.saveEntry({ dueDay: this.entry.dueDay })
            },

            modify () {
                this.$emit('modify', this.entry)
            }
        }
    }
</script>

<style lang="scss" scoped>
    .amount-width {
        width: 125px;
    }
<style>
