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
            <f-button variant="secondary" @click="cancel" outline>Cancel</f-button>
            <f-button @click="submit">Save</f-button>
        </template>
    </f-card>

</template>

<script>
    import moment from 'moment'

    export default {

        components: {},

        props: {
            budget: {
                type: Object
            },
            entry: {
                type: Object,
                default: null
            }
        },

        data () {
            return {
                // entry: {
                //     name: '',
                //     autoPay: false,
                //     dueDay: null,
                //     amount: 0.00,
                //     incomeId: 'second',
                //     groupId: null,
                //     url: ''
                // },
                autoPayOn: false,
                incomes: [],
                groups: []
            }
        },

        computed: {
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
            updateDueDay (value) {
                this.entry.dueDay = moment(value).format('D')
            },
            async loadData () {
                try {
                    const { data: incomes } = await this.$http.getIncomes(this.budget.month)
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
                    console.log(error)
                }
            },
            toggleAutoPay () {
                this.autoPayOn = !this.autoPayOn
            },
            cancel () {
                this.$emit('cancel')
            },
            submit () {
                this.$store.commit('loading', true)
                this.$http.updateEntry(this.budget.month, this.entry.id, this.entry).then(({ data }) => {
                    this.$store.commit('loading', false)
                    this.$emit('updated', this.entry)
                }).catch(({ error }) => {
                    console.log('error', error)
                })
            }
        },

        mounted () {
            this.loadData()
        }

    }

</script>
