<template>
    <vuejs-datepicker :value="value"
                      :inline="inline"
                      :format="format"
                      :wrapper-class="wrapper"
                      :calendar-class="calendar"
                      :disabled-dates="state.disabledDates"
                      @input="input">
    </vuejs-datepicker>
</template>

<script>
    // Datepicker docs here
    // https://github.com/charliekassel/vuejs-datepicker
    import VuejsDatepicker from 'vuejs-datepicker';
    import moment from "moment";

    export default {

        components: {
            VuejsDatepicker
        },

        props: {
            value: {
                type: [String, Number, Date],
            },
            format: {
                type: [String, Function]
            },
            inline: {
                type: Boolean,
                default: false
            },
            minDate: {
                type: [String, Date]
            },
            maxDate: {
                type: [String, Date]
            }
        },

        computed: {
            wrapper () {
                const classes = []

                classes.push('mt-1 rounded-md shadow-md')

                return classes
            },
            calendar () {
                const classes = []

                classes.push('border-2 border-400 rounded-md')

                return classes
            },
            state () {
                const state = {
                    disabledDates: {}
                }

                if (this.minDate) {
                    state.disabledDates.to = this.minDate
                }

                if (this.maxDate) {
                    state.disabledDates.from = this.maxDate
                }

                return state
            }
        },

        methods: {
            input (value) {
                this.$emit('input', value)
            }
        }

    }

</script>
