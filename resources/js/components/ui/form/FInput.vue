<template>

    <div>
        <div class="flex justify-between">
            <label v-if="label" :class="labelClasses">{{ label }}</label>
            <span v-if="hint" class="text-sm leading-5 text-gray-500 pr-2" id="email-optional">{{ hint }}</span>
        </div>
        <div :class="wrapperClasses">
            <div v-if="showLeftIcon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <icon :name="leftIcon" fill class="h-5 w-5 text-gray-400"/>
            </div>
            <span v-if="showLeftAddOn" class="inline-flex items-center px-3 text-gray-600 bg-gray-200 border-2 border-gray-400 rounded-l-md left-0 px-3 flex items-center pointer-events-none">
                http://
            </span>
            <input :type="type" :class="inputClasses" :value="value" :placeholder="placeholder">
            <span v-if="showRightAddOn" class="inline-flex items-center px-3 text-gray-600 bg-gray-200 border-2 border-gray-400 rounded-r-md left-0 px-3 flex items-center pointer-events-none">
                http://
            </span>
            <div v-if="showRightIcon" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm sm:leading-5" id="price-currency">
                    <icon :name="rightIcon" fill class="h-5 w-5 text-gray-400"/>
                </span>
            </div>
            <div v-if="showError" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <icon name="exclamationCircle" stroke class="h-5 w-5 text-red-600"/>
            </div>
            <div v-if="showSuccess" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <icon name="thumbUp" stroke class="h-5 w-5 text-green-600"/>
            </div>
        </div>
        <p v-if="showError" class="mt-1 pl-2 text-sm text-red-600">{{ feedback }}</p>
        <p v-if="showSuccess" class="mt-1 pl-2 text-sm text-green-600">{{ feedback }}</p>
        <p v-if="showDescription" class="mt-1 pl-2 text-sm text-gray-500" id="email-description">{{ description }}</p>
    </div>

</template>

<script>

    export default {

        props: {
            label: {
                description: 'The group label',
                type: String,
                default: null
            },
            type: {
                description: 'Sets the input type',
                type: String,
                default: 'text'
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
            hint: {
                description: 'A hint label that will appear just above the right side of the input',
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
            value: {
                description: 'Set the value of the input. This is the other end of the v-model directive',
                type: [Number, String]
            },
            placeholder: {
                description: 'The field placeholder',
                type: String
            },
            leftIcon: {
                type: String,
                default: null
            },
            rightIcon: {
                type: String,
                default: null
            },
            leftAddOn: {
                type: String,
                default: null
            },
            rightAddOn: {
                type: String,
                default: null
            }
        },

        data () {
            return {
                // wrapperBaseClasses: 'sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5',
                wrapperBaseClasses: 'mt-1 rounded-md shadow-sm',
                labelBaseClasses: 'block text-sm font-medium leading-5 text-gray-700 pl-2',
                inputBaseClasses: 'bg-white border-2 border-gray-400 rounded-md py-2 px-4 block w-full leading-normal focus:outline-none focus:shadow-outline'
            }
        },

        computed: {
            showLeftAddOn () {
                return this.leftAddOn && !this.leftIcon && !this.error && !this.success
            },
            showRightAddOn () {
                return this.rightAddOn && !this.leftIcon && !this.error && !this.success
            },
            showLeftIcon () {
                return this.leftIcon
            },
            showRightIcon () {
                return this.rightIcon && !this.error && !this.success
            },
            showError () {
                return this.error
            },
            showSuccess () {
                return this.success && !this.error
            },
            showDescription () {
                return this.description && !this.error && !this.success
            },
            wrapperClasses () {
                const classes = []

                classes.push(this.wrapperBaseClasses)

                if (this.leftIcon || this.rightIcon || this.error || this.success) {
                    classes.push('relative')
                } else if (this.leftAddOn || this.rightAddOn) {
                    classes.push('flex')
                    if (this.leftAddOn) {
                        classes.push('rounded-l-0')
                    }
                    if (this.rightAddOn) {
                        classes.push('rounded-r-0')
                    }
                } else {
                    classes.push('relative')
                }

                return classes
            },
            inputClasses () {
                const classes = []

                classes.push(this.inputBaseClasses)

                if (this.leftIcon || this.rightIcon || this.error || this.success) {
                    if (this.leftIcon) {
                        classes.push('pl-10')
                    }
                    if (this.rightIcon) {
                        classes.push('pr-10')
                    }
                } else if (this.leftAddOn || this.rightAddOn) {
                    classes.push('flex-1')
                    if (this.leftAddOn) {
                        classes.push('rounded-l-none border-l-0')
                    }
                    if (this.rightAddOn) {
                        classes.push('rounded-r-none border-r-0')
                    }
                }

                return classes
            },
            // iconWrapperClasses () {
            //     const classes = []
            //
            //     if (this.leftAddOn) {
            //         classes.push('absolute')
            //     } else {
            //         classes.push('absolute')
            //     }
            //
            //     return classes
            // },
            labelClasses () {
                const classes = []

                classes.push(this.labelBaseClasses)

                return classes
            }
            // variant () {
            //     return {
            //         success: this.success,
            //         error: this.error
            //     }
            // },
            // hasFeedback () {
            //     if (this.success || this.error) {
            //         return this.feedback
            //     }
            // },
            // getInputClasses () {
            //     const classes = [this.inputClasses]
            //
            //     if (this.right) {
            //         classes.push('text-right')
            //     }
            //
            //     return classes
            // }
        },

        methods: {
            input (value) {
                this.$emit('input', value)
            }
        }

    }

</script>
