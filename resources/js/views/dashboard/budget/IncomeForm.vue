<template>
    <div>
        <budget-right-header>
            <template v-slot:left>
                <ub-button v-if="income.id" @click="deleteIncome" outline variant="danger" icon="trash" class="float-left" size="sm"></ub-button>
            </template>
            <template v-slot:right>
                <ub-button variant="secondary" @click="cancel" outline size="sm">Cancel</ub-button>
                <ub-button @click="save" size="sm">Save</ub-button>
            </template>
        </budget-right-header>
        <div class="bg-gray-100 border border-gray-300 rounded-md shadow-md p-4 grid grid-cols-2 gap-6">
            <div class="col-span-2 text-lg text-center font-bold">Income Details</div>
            <div class="col-span-2">
                <f-input label="Income Name"
                         placeholder="entry name"
                         v-model="income.name">
                </f-input>
            </div>
            <div class="col-span-1">
                <f-input label="Due Day" placeholder="1" right v-model="income.dueDay" :right-add-on="dueDaySuffix"></f-input>
            </div>
            <div class="col-span-1">
                <f-input label="Amount" placeholder="0.00" right v-model="income.amount" left-add-on="$"></f-input>
            </div>
        </div>
        <modal v-if="showDelete" variant="danger" title="Are you sure?" confirm-label="Yes, Delete!" cancel-label="Oops! No" @confirm="deleteConfirmed" @cancel="deleteCanceled">
            Do you really want to delete this income? It can't be undone.
        </modal>
    </div>
</template>

<script>
    import moment from 'moment'
    import Modal from "@/components/ui/modal/Modal";
    import BudgetRightHeader from '@/views/dashboard/budget/BudgetRightHeader'
    import { mapGetters } from 'vuex'

    export default {

        components: {
            Modal,
            BudgetRightHeader
        },

        props: {
            income: {
                type: Object,
                default: null
            }
        },

        data () {
            return {
                showDelete: false,
                originalIncome: null
            }
        },

        computed: {

            ...mapGetters(['budget']),

            date () {
                let dueDay = this.income.dueDay ? this.income.dueDay : '1'
                const date = moment(this.budget.month, "YYYY-M-DD").day(dueDay)
                const year = date.format('YYYY')
                const month = date.format('MM');
                return moment(`${year}-${month}-${dueDay}`, "YYYY-M-DD")
            },

            dueDaySuffix () {
                const day = this.date.format('Do')
                return day.slice(-2)
            }
        },

        methods: {
            async save () {
                this.$store.commit('loading', true)
                try {
                    if (this.income.id) {
                        const { data } = await this.$http.updateIncome(this.budget.month, this.income.id, {
                            name: this.income.name,
                            dueDay: parseInt(this.income.dueDay),
                            amount: parseFloat(this.income.amount)
                        })
                    } else {
                        const { data } = await this.$http.addIncome(this.budget.month, {
                            name: this.income.name,
                            dueDay: parseInt(this.income.dueDay),
                            amount: parseFloat(this.income.amount)
                        })
                    }
                    this.$store.commit('loading', false)
                    this.$emit('done', true)
                } catch ({ error }) {
                    console.log('error', error)
                    this.$store.commit('loading', false)
                    this.$emit('done', true)
                }
            },
            cancel () {
                // Reset this.income to it's original state
                for (const [key, value] of Object.entries(this.originalIncome)) {
                    this.income[key] = value
                }

                this.$emit('done', false)
            },
            deleteIncome () {
                this.showDelete = true
            },
            async deleteConfirmed () {
                try {
                    await this.$http.deleteIncome(this.budget.month, this.income.id)
                    this.$emit('done', true)
                    this.showDelete = false
                } catch ({ error }) {
                    this.$emit('done', true)
                    this.showDelete = false
                }
            },
            deleteCanceled () {
                this.showDelete = false
            }

        },

        mounted () {
            this.originalIncome = { ...this.income }
        }
    }

</script>
