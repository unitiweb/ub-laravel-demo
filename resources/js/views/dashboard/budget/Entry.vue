<template>
    <f-card title="Entry Details" sub-title="This is a test and only a test">

        <div class="grid grid-cols-2 gap-6">
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
            <div class="col-span-2 sm:col-span-1 sm:row-span-3 mx-auto">
                <f-datepicker :value="dueDate"
                              inline
                              format="dd"
                              :min-date="minDate"
                              :max-date="maxDate"
                              @input="updateDueDay">
                </f-datepicker>
            </div>
            <div class="col-span-2 sm:col-span-1">
                <f-input label="Amount" placeholder="0.00" right v-model="entry.amount" left-add-on="$"></f-input>
            </div>
            <div class="col-span-2 sm:col-span-1">
                <f-select label="Income" v-model="entry.incomeId" :options="incomes" placeholder="select income"/>
            </div>
            <div class="col-span-2 sm:col-span-1">
                <f-select label="Group" v-model="entry.groupId" :options="groups" placeholder="select group"/>
            </div>
            <div class="col-span-2">
                <f-input label="Url" placeholder="http://" right v-model="entry.url"></f-input>
            </div>
        </div>

        <template v-slot:buttons>
            <ub-button variant="secondary" @click="cancel" outline>Cancel</ub-button>
            <ub-button @click="save">Save</ub-button>
        </template>
    </f-card>
</template>

<script>
    import Layout from "@/components/layouts/Layout"
    import moment from "moment";

    export default {

        components: {
            Layout
        },

        data () {
            return {
                entry: {
                    id: null,
                    name: '',
                    autoPay: false,
                    dueDay: 1,
                    amount: 0.00,
                    incomeId: null,
                    groupId: null,
                    url: '',
                },
                incomes: [],
                groups: []
            }
        },

        computed: {

            year () {
                return this.$route.params.year
            },

            month () {
                return this.$route.params.month
            },

            budgetDate () {
                return moment(`${this.year}-${this.month}-01`, 'YYYY-MM-DD').format('YYYY-MM-DD')
            },

            dueDate () {
                if (!this.entry.dueDay) {
                    this.entry.dueDay = 1
                }
                return moment().date(this.entry.dueDay).toDate()
            },

            minDate () {
                return moment().startOf('month').toDate()
            },

            maxDate () {
                return moment().endOf('month').toDate()
            }
        },

        methods: {

            cancel () {
                this.$router.push({ name: 'budget', params: { year: this.year, month: this.month }})
            },

            updateDueDay () {

            },

            goBack () {
                if (this.$store.getters.budgetView === 'groups') {
                    this.$router.push({ name: 'view-groups' })
                } else {
                    this.$router.push({ name: 'view-incomes' })
                }
            },

            save () {
                this.id !== null ? this.update() : this.create()
                this.goBack()
            },

            update () {
                this.$http.updateEntry(this.$store.getters.budgetDate, this.entry.id, {
                    name: this.entry.name,
                    autoPay: this.entry.autoPay,
                    dueDay: this.entry.dueDay,
                    amount: this.entry.amount,
                    incomeId: this.entry.incomeId,
                    groupId: this.entry.groupId,
                    url: this.entry.url,
                }).then(({ data }) => {
                    this.$emit('updatedEntry', this.entry)
                }).catch(({ error }) => {
                    console.log('error', error)
                })
            },

            create () {

            },

            async loadEntry () {
                try {
                    // this.entry = this.defaultEntry
                    const { data } = await this.$http.getEntry(this.$store.getters.budgetDate, this.$route.params.id)
                    this.entry = data
                } catch ({ error }) {
                    console.log('error', error)
                }
            },

            async loadData () {
                try {
                    const { data: incomes } = await this.$http.getIncomes(this.budgetDate)
                    const { data: groups } = await this.$http.getGroups()

                    this.incomes = []
                    for (let i = 0; i < incomes.length; i++) {
                        this.incomes.push({ id: incomes[i].id, label: incomes[i].name })
                    }

                    this.groups = []
                    for (let i = 0; i < groups.length; i++) {
                        this.groups.push({ id: groups[i].id, label: groups[i].name })
                    }
                } catch (error) {
                    console.log('error', error)
                }
            }

        },

        async mounted () {
            await this.loadEntry()
            await this.loadData()
        }

    }

</script>
