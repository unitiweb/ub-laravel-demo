<template>
    <div class="flex bg-white border-b rounded-md">
        <div class="flex-none border-r px-1 py-1">
            <icon name="switchVertical" class="entry-handle cursor-move text-gray-500 hover:text-blue-700 h-5 h-5"></icon>
            <icon @click="modify" name="pencilAlt" class="cursor-pointer text-gray-500 hover:text-blue-700 mt-1 h-5 h-5"></icon>
        </div>
        <div class="flex-1 px-2">
            <edit-in-place classes="py-1 px-1 hover:bg-gray-200" v-model="entry.name" :width="200" @updated="updateName"/>
            <div class="text-xs text-gray-600 pl-1">
                Due:
                <t-datepicker
                    :value="dueDate"
                    placeholder="Select a date"
                    date-format="Y-m-d"
                    user-format="J"
                    :clearable="false"
                    @input="updateDueDay"
                />
            </div>
        </div>
        <div class="flex-none hover:bg-blue-100">
            <edit-in-place classes="text-right text-lg amount-width px-3 py-3" v-model="entry.amount" :width="125" currency select-on-edit @updated="updateAmount"/>
        </div>
        <div class="flex-none rounded-md">
            <entry-progress v-model="entry" :month="this.month" @updated="updateStatus"></entry-progress>
        </div>
    </div>
</template>

<script>
    import Currency from '@/components/ui/Currency'
    import DueDay from '@/components/ui/DueDay'
    import EntryProgress from '@/components/budget/EntryProgress'
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
            }
        },

        data () {
            return {
                status: {
                    none: false,
                    goal: false,
                    paid: false,
                    cleared: false
                },
                edit: null
            }
        },

        computed: {
            isGoal () {
                return this.status.goal
            },
            isPaid () {
                return this.status.paid
            },
            isCleared () {
                return this.status.cleared
            },
            dueDate () {
                const date = moment(new Date())
                date.date(this.entry.dueDay)
                return date.format('YYYY-MM-DD')
                // return moment().date(this.entry.dueDay).format('Y-m-d')
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
