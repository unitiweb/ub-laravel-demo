<template>
    <f-input :label="label" :placeholder="placeholder">
        <template v-slot:left-add-on>
            <f-checkbox :value="isChecked" @input="checkboxChange" class="mx-2 text-lg"></f-checkbox>
        </template>
        <f-datepicker v-if="value"
                      :value="pickerDate"
                      :inline="inline"
                      format="MMM dsu, yyyy"
                      @input="pickerUpdate">
        </f-datepicker>
    </f-input>
</template>

<script>
import moment from "moment";

export default {

    props: {
        label: {
            type: String,
        },
        value: {
            type: String
        },
        placeholder: {
            type: String
        },
        inline: {
            type: Boolean,
            default: false
        },
        checked: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        pickerDate() {
            return moment(this.value).toDate()
        },

        isChecked () {
            return !!this.value
        }
    },

    methods: {
        /**
         * Set the changeDueDay's new dueDay value from the dialog picker
         */
        pickerUpdate (value) {
            value = moment(value).format('YYYY-MM-DD')
            this.$emit('input', value)
        },

        checkboxChange () {
            console.log('checked')
            const checked = !this.checked
            this.$emit('checked', checked)
        }
    }

}

</script>
