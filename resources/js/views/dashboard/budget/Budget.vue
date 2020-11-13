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
        <div>
            <div class="border p-6" style="height: 700px;">



                <f-button @click="prev">Prev</f-button>
                <f-button @click="next">Next</f-button>
                <f-button @click="refresh">Refresh</f-button>

                <div class="my-3">

                    <entry v-for="(entry, index) in budget.entries" :key="`${entry}-${index}`" :entry="entry"></entry>

                </div>


            </div>
        </div>

    </layout>

</template>

<script>
    import Layout from "@/components/layouts/Layout";
    import moment from 'moment'
    import Entry from '@/components/entry/Entry'

    export default {

        components: {
            Entry,
            Layout
        },

        data () {
            return {
                budget: {}
            }
        },

        computed: {
            budgetDate () {
                const year = this.$route.params.year
                const month = this.$route.params.month
                return moment(`${year}-${month}-01`, 'YYYY-MM-DD').format('YYYY-MM-DD')
            }
        },

        watch: {
            $route () {
                this.loadBudget()
            }
        },

        methods: {
            next () {
                this.$router.push({ name: 'budget', params: { year: '2020', month: '11' }})
            },
            prev () {
                this.$router.push({ name: 'budget', params: { year: '2020', month: '10' }})
            },
            refresh () {
                this.loadBudget()
            },
            loadBudget () {
                this.redirectIfNoDate()
                this.budget = {}
                this.$store.commit('loading', true)
                this.$http.getBudget(this.budgetDate, 'incomes,groups,entries')
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
            }
        },

        mounted () {
            this.loadBudget()
        }

    }
</script>
