<template>
    <t-input-group :variant="variant" :label="label" :description="description" :feedback="hasFeedback">
        <t-input
            :variant="variant"
            :value="value"
            :type="type"
            :placeholder="placeholder"
            @input="input"
        />
    </t-input-group>
</template>

<script>

    export default {

        props: {
            label: {
                type: String,
                default: null
            },
            description: {
                type: String,
                default: null
            },
            feedback: {
                type: String,
                default: null
            },
            success: {
                type: Boolean,
                default: false
            },
            error: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: 'text'
            },
            value: {
                type: [Number, String]
            },
            placeholder: {
                type: String
            }
        },

        computed: {
            variant () {
                return {
                    success: this.success,
                    error: this.error
                }
            },
            hasFeedback () {
                if (this.success || this.error) {
                    return this.feedback
                }
            }
        },

        methods: {
            input (value) {
                this.$emit('input', value)
            }
        }

    }

</script>
