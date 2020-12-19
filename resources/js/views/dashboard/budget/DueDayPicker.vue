<template>
    <f-input label="Due Day">
        <f-datepicker :value="pickerDueDate"
                      :inline="inline"
                      format="dsu"
                      :min-date="pickerMinDate"
                      :max-date="pickerMaxDate"
                      @input="pickerUpdateDueDay">
        </f-datepicker>
    </f-input>
</template>

<script>
    import moment from "moment";

    export default {

        props: {
            value: {
                type: [String, Number]
            },
            date: {
                type: String
            },
            inline: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            pickerDueDate() {
                return moment(this.date).date(this.value).toDate()
            },
            pickerMinDate () {
                return moment(this.date).startOf('month').toDate()
            },
            pickerMaxDate () {
                return moment(this.date).endOf('month').toDate()
            }
        },

        methods: {
            /**
             * Set the changeDueDay's new dueDay value from the dialog picker
             */
            pickerUpdateDueDay (value) {
                value = moment(value).format('D')
                this.$emit('input', value)
            }
        }

    }

</script>
