<template>
    <div class="p-2">
        <div v-if="entry" class="grid grid-cols-2 gap-6">
            <div v-if="errors.length >= 1" class="col-span-2">
                {{ errors[0] }}
            </div>
            <div class="col-span-2">
                <f-input label="Entry Name" placeholder="entry name" v-model="entry.name">
                    <template v-slot:right-add-on>
                        <div class="flex items-center">
                            <input id="dueDay" type="checkbox" v-model="entry.autoPay" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                            <label for="dueDay" class="ml-2 block text-sm leading-5 text-gray-900">
                                AutoPay
                            </label>
                        </div>
                    </template>
                </f-input>
            </div>
            <div class="col-span-1">
                <f-input label="Due Day" placeholder="1" right v-model="entry.dueDay" :right-add-on="dueDaySuffix"></f-input>
            </div>
            <div class="col-span-1">
                <f-input label="Amount" placeholder="0.00" v-model="entry.amount" left-add-on="$"></f-input>
            </div>
            <div class="col-span-2">
                <f-select label="Income" v-model="entry.incomeId" :options="incomes" placeholder="select income"/>
            </div>
            <div class="col-span-2">
                <f-select label="Group" v-model="entry.groupId" :options="groups" placeholder="select group"/>
            </div>
            <div class="col-span-2">
                <f-input label="Url" placeholder="http://" v-model="entry.url"></f-input>
            </div>
            <div class="col-span-1">
                <ub-button v-if="entry.id" @click="deleteEntry" outline variant="danger" class="float-left">delete</ub-button>
            </div>
            <div class="col-span-1 text-right">
                <ub-button variant="secondary" @click="cancel" outline>Cancel</ub-button>
                <ub-button @click="save">Save</ub-button>
            </div>
        </div>
        <modal v-if="showDelete" variant="danger" title="Are you sure?" confirm-label="Yes, Delete!" cancel-label="Oops! No" @confirm="deleteEntryConfirmed" @cancel="deleteEntryCanceled">
            Do you really want to delete this entry? It can't be undone.
        </modal>
    </div>
</template>

<script>
    import moment from "moment";
    import Modal from "@/components/ui/modal/Modal";

    export default {

        components: {
            Modal
        },

        props: {
            entry: {
                type: Object
            }
        },

        data () {
            return {
                autoPayOn: false,
                incomes: [],
                groups: [],
                showDelete: false,
                errors: []
            }
        },

        computed: {
            year () {
                return this.$route.params.year
            },
            month () {
                return this.$route.params.month
            },
            date () {
                let dueDay = this.entry.dueDay ? this.entry.dueDay : '01'
                return moment(`${this.year}-${this.month}-${dueDay}`, "YYYY-M-DD")
            },
            budgetMonth () {
                return this.date.format('YYYY-MM-DD')
            },
            dueDate () {
                if (!this.entry.dueDay) {
                    this.entry.dueDay = 1
                }
                return this.date.date(this.entry.dueDay).toDate()
            },
            minDate () {
                return this.date.startOf('month').toDate()
            },
            maxDate () {
                return this.date.endOf('month').toDate()
            },
            dueDaySuffix () {
                const day = this.date.format('Do')
                return day.slice(-2)
            }
        },

        methods: {
            async loadData () {
                try {
                    const { data: incomes } = await this.$http.getIncomes(this.budgetMonth)
                    const { data: groups } = await this.$http.getGroups()

                    this.incomes = []
                    for (let i = 0; i < incomes.length; i++) {
                        this.incomes.push({ id: incomes[i].id, label: incomes[i].name })
                    }
                    this.incomes.push({ id: null, label: 'none' })

                    this.groups = []
                    for (let i = 0; i < groups.length; i++) {
                        this.groups.push({ id: groups[i].id, label: groups[i].name })
                    }
                    this.groups.push({ id: null, label: 'none' })

                } catch (error) {
                    console.log(error)
                }
            },
            cancel () {
                this.$emit('done', false)
            },
            async save () {
                this.$store.commit('loading', true)
                try {
                    if (this.entry.id) {
                        await this.$http.updateEntry(this.budgetMonth, this.entry.id, this.entry)
                        this.$store.commit('loading', false)
                        this.$emit('done', true)
                    } else {
                        const data = await this.$http.addEntry(this.budgetMonth, this.entry)
                        this.$store.commit('loading', false)
                        this.$emit('done', true)
                    }
                } catch ({ error }) {
                    this.$store.commit('loading', false)
                    if (error.code === 422) {

                        console.log('error', error)
                    }
                }
            },
            deleteEntry () {
                this.showDelete = true
            },
            async deleteEntryConfirmed () {
                const { data } = await this.$http.deleteEntry(this.budgetMonth, this.entry.id)
                this.$emit('done', true)
                this.showDelete = false
            },
            deleteEntryCanceled () {
                this.showDelete = false
            }
        },

        mounted () {
            this.loadData()
        }

    }

</script>
