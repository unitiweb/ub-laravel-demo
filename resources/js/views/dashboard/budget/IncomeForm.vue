<template>
    <div class="p-2">
        <div v-if="income" class="grid grid-cols-2 gap-6">
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
            <div class="col-span-1">
                <ub-button v-if="income.id" @click="deleteIncome" outline variant="danger" class="float-left">delete</ub-button>
            </div>
            <div class="col-span-1 text-right">
                <ub-button variant="secondary" @click="cancel" outline>Cancel</ub-button>
                <ub-button @click="save">Save</ub-button>
            </div>
        </div>
        <modal v-if="showDelete" variant="danger" title="Are you sure?" confirm-label="Yes, Delete!" cancel-label="Oops! No" @confirm="deleteConfirmed" @cancel="deleteCanceled">
            Do you really want to delete this entry? It can't be undone.
        </modal>
    </div>
</template>

<script>
    import moment from 'moment'
    import Modal from "@/components/ui/modal/Modal";

    export default {

        components: {
            Modal
        },

        props: {
            income: {
                type: Object,
                default: null
            }
        },

        data () {
            return {
                showDelete: false
            }
        },

        computed: {
            year () {
                return this.$route.params.year
            },
            month () {
                return this.$route.params.month
            },
            budgetMonth () {
                return `${this.year}-${this.month}-01`
            },
            date () {
                let dueDay = this.income.dueDay ? this.income.dueDay : '1'
                return moment(`${this.year}-${this.month}-${dueDay}`, "YYYY-M-DD")
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
                        const { data } = await this.$http.updateIncome(this.budgetMonth, this.income.id, {
                            name: this.income.name,
                            dueDay: parseInt(this.income.dueDay),
                            amount: parseFloat(this.income.amount)
                        })
                    } else {
                        const { data } = await this.$http.addIncome(this.budgetMonth, {
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
                this.$emit('done', false)
            },
            deleteIncome () {
                this.showDelete = true
            },
            async deleteConfirmed () {
                try {
                    await this.$http.deleteIncome(this.budgetMonth, this.income.id)
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
            console.log('income', this.income)
        }
    }

</script>
