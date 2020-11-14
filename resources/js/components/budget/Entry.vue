<template>
    <div class="flex bg-white border-b rounded-md">
        <div class="flex-none hover:bg-blue-200 py-1">
            <icon name="order-bar" class="cursor-move text-gray-700 h-6 w-4"></icon>
            <icon name="link" class="cursor-pointer float-left ml-1 mt-1 h-3 w-3"></icon>
        </div>
        <div class="flex-1 px-3" style="width: 50%;">
            <div v-if="!edit" @click="toggleName" class="hover:bg-gray-100 cursor-pointer py-1">
                {{ entry.name }}
                <div class="text-xs text-gray-600">
                    <span class="float-left">Due: <due-day :value="entry.dueDay"></due-day></span>
                </div>
            </div>
            <div v-if="edit === 'name'">
                <entry-input v-model="entry.name" :width="200" class="h-4" @update="updateName"></entry-input>
            </div>
        </div>
        <div class="flex-none text-lg text-right cursor-pointer hover:bg-blue-100 amount-width">
            <div v-if="!edit" class="px-3 py-3" @click="toggleAmount">
                <currency :value="entry.amount"/>
            </div>
            <div v-if="edit === 'amount'" class="p-0">
                <entry-input v-model="entry.amount" class="h-4" right @update="updateAmount"></entry-input>
            </div>
        </div>
        <div class="flex-none rounded-md">
            <entry-progress v-model="entry" :month="this.month"></entry-progress>
        </div>
    </div>
</template>

<script>
    import Currency from '@/components/ui/Currency'
    import DueDay from '@/components/ui/DueDay'
    import EntryProgress from '@/components/budget/EntryProgress'
    import EntryInput from '@/components/budget/EntryInput'
    import { cleanNumber } from '@/scripts/helpers/utils'

    export default {

        components: {
            EntryInput,
            Currency,
            DueDay,
            EntryProgress
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
            }
        },

        methods: {
            toggleAmount () {
                this.edit = !this.edit ? 'amount' : null
            },
            updateAmount (value) {
                if (value === null) {
                    this.edit = null
                } else {
                    this.entry.amount = cleanNumber(value)
                    this.saveEntry({ amount: this.entry.amount })
                }
            },
            toggleName () {
                this.edit = !this.edit ? 'name' : null
            },
            updateName (value) {
                if (value === null) {
                    this.edit = null
                } else {
                    this.entry.name = value
                    this.saveEntry({ name: this.entry.name })
                }
            },
            saveEntry (values) {
                this.$http.updateEntry(this.month, this.entry.id, { ...values })
                    .then(({ data }) => {
                        this.edit = null
                    }).catch(({ error }) => {
                        console.log('error', error)
                        this.edit = null
                    })
            }
        }

    }
</script>

<style lang="scss" scoped>
    .amount-width {
        width: 125px;
    }
<style>
