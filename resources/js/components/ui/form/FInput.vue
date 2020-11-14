<template>
    <t-input-group :variant="variant" :label="label" :description="description" :feedback="hasFeedback">
        <t-input
            :variant="variant"
            :value="value"
            :type="type"
            :placeholder="placeholder"
            :class="getInputClasses"
            @input="input"
        />
    </t-input-group>
</template>

<script>

    export default {

        props: {
            label: {
                description: 'The group label',
                type: String,
                default: null
            },
            description: {
                description: 'A description that will appear under the field',
                type: String,
                default: null
            },
            feedback: {
                description: 'A feedback message that will show at the bottom formatted with the variant type',
                type: String,
                default: null
            },
            success: {
                description: 'Put the input in success style mode',
                type: Boolean,
                default: false
            },
            error: {
                description: 'Put the input in error style mode',
                type: Boolean,
                default: false
            },
            type: {
                description: 'Sets the input type',
                type: String,
                default: 'text'
            },
            value: {
                description: 'Set the value of the input. This is the other end of the v-model directive',
                type: [Number, String]
            },
            placeholder: {
                description: 'The field placeholder',
                type: String
            },
            right: {
                description: 'To align the input test to the right',
                type: Boolean,
                default: false
            },
            inputClasses: {
                description: 'Classes to be added to the input component',
                type: String,
                default: ''
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
            },
            getInputClasses () {
                const classes = [this.inputClasses]

                if (this.right) {
                    classes.push('text-right')
                }

                return classes
            }
        },

        methods: {
            input (value) {
                this.$emit('input', value)
            }
        }

    }

</script>
