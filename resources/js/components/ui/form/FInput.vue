<template>

    <div>
        <div class="flex justify-between">
            <label v-if="label" :class="labelClasses">{{ label }}</label>
            <span v-if="hint" class="text-sm leading-5 text-gray-500 pr-2" id="email-optional">{{ hint }}</span>
        </div>
        <div :class="wrapperClasses">
            <div v-if="hasLeftAddOn" class="absolute inset-y-0 left-0 flex items-center">
                <slot name="left-add-on"></slot>
            </div>
            <div v-if="showLeftIcon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <icon :name="leftIcon" fill class="text-gray-400"/>
            </div>
            <span v-if="showLeftAddOn" class="inline-flex items-center px-3 text-gray-600 bg-gray-200 border border-gray-400 rounded-l-md left-0 px-3 flex items-center pointer-events-none">
                {{ leftAddOn }}
            </span>
            <div v-if="!!this.$slots.default" :class="inputClasses">
                <slot/>
            </div>
            <input v-if="!!!this.$slots.default" :type="type" :class="inputClasses" :value="getValue" @input="input" :placeholder="placeholder" :disabled="disabled">
            <span v-if="showRightAddOn" class="inline-flex items-center px-3 text-gray-600 bg-gray-200 border border-gray-400 rounded-r-md left-0 px-3 flex items-center pointer-events-none">
                {{ rightAddOn }}
            </span>
            <div v-if="showRightIcon" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm sm:leading-5" id="price-currency">
                    <icon :name="rightIcon" fill class="text-gray-400"/>
                </span>
            </div>
            <div v-if="hasError" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <icon name="exclamationCircle" stroke class="text-red-600"/>
            </div>
            <div v-if="isSuccess" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <icon name="thumbUp" stroke class="text-green-600"/>
            </div>
            <div v-if="hasRightAddOn" class="absolute inset-y-0 mr-2 right-0 flex items-center">
                <slot name="right-add-on"></slot>
            </div>
        </div>
        <p v-if="hasError" class="mt-1 pl-2 text-sm text-red-600">
            {{ getError }}
        </p>
        <p v-if="isSuccess" class="mt-1 pl-2 text-sm text-green-600">{{ success }}</p>
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
            validator: {
                type: Object
            },
            feedback: {
                description: 'A feedback message that will show at the bottom formatted with the variant type',
                type: String,
                default: null
            },
            success: {
                description: 'A message to show if validation is all good',
                type: String
            },
            enableSuccess: {
                description: 'Enable the success state',
                type: Boolean,
                default: true
            },
            hint: {
                description: 'A hint label that will appear just above the right side of the input',
                type: String,
                default: null
            },
            error: {
                description: 'Put the input in error style mode',
                type: String
            },
            disabled: {
                description: 'Make the input disabled',
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
            right: {
                type: Boolean,
                default: false
            },
            center: {
                type: Boolean,
                default: false
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
            },
            filter: {
                type: Function,
            }
        },

        data () {
            return {
                // wrapperBaseClasses: 'sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5',
                wrapperBaseClasses: 'mt-1 rounded-md shadow-sm',
                labelBaseClasses: 'block text-sm font-medium leading-5 text-gray-700 pl-2',
                inputBaseClasses: 'bg-white border border-gray-400 rounded-md py-2 px-4 block w-full leading-normal focus:outline-none focus:shadow-outline'
            }
        },

        computed: {
            hasError () {
                if (this.validator) {
                    return this.validator.$error
                }
                return this.error
            },
            isSuccess () {
                if (this.validator && this.enableSuccess) {
                    return !this.validator.$error && this.validator.$dirty
                }
                return !!this.success
            },
            getError () {
                if (this.validator) {
                    if (this.validator.$error) {
                        return this.error
                    }
                }
            },
            getValue () {
                if (this.filter) {
                    return this.filter(this.value)
                }
                return this.value
            },
            showLeftAddOn () {
                return this.leftAddOn && !this.leftIcon && !this.error && !this.isSuccess
            },
            showRightAddOn () {
                return this.rightAddOn && !this.leftIcon && !this.error && !this.isSuccess
            },
            showLeftIcon () {
                return this.leftIcon
            },
            showRightIcon () {
                return this.rightIcon && !this.hasError && !this.isSuccess
            },
            showError () {
                return this.error
            },
            // showSuccess () {
            //     return this.success && !this.error
            // },
            showDescription () {
                return this.description && !this.hasError && !this.isSuccess
            },
            hasLeftAddOn () {
                return !!this.$slots['left-add-on'] && !this.leftIcon && !this.leftAddOn
            },
            hasRightAddOn () {
                return !!this.$slots['right-add-on'] && !this.rightIcon && !this.error && !this.isSuccess
            },
            wrapperClasses () {
                const classes = []

                classes.push(this.wrapperBaseClasses)

                if (this.leftIcon || this.rightIcon || this.hasError || this.isSuccess) {
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

                if (this.leftIcon || this.rightIcon || this.hasError || this.isSuccess) {
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

                if (this.right) {
                    classes.push('text-right')
                } else if (this.center) {
                    classes.push('text-center')
                }

                // Make room on the right for the error icon
                if (this.hasError) {
                    classes.push('pr-10 bg-red-100 border-green-400')
                }

                if (this.isSuccess) {
                    classes.push('pr-10 bg-green-100 border-green-400')
                }

                if (this.disabled) {
                    classes.push('bg-gray-100 text-gray-500 cursor-not-allowed')
                }

                return classes
            },
            labelClasses () {
                const classes = []

                classes.push(this.labelBaseClasses)

                return classes
            }
        },

        methods: {
            checkError (errors, key, message) {
                if (this.validator.hasOwnProperty(key)) {
                    if (this.validator[key] === false) {
                        errors.push(message)
                    }
                }
            },
            input (e) {
                this.$emit('input', e.target.value)
            }
        }

    }

</script>
