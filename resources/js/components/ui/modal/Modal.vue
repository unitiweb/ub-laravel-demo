<template>
    <transition enter-active-class="ease-out duration-100"
                enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                leave-active-class="ease-in duration-100"
                leave-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div :class="`bg-${icon.color}-200`" class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                <icon :name="icon.image" size="6" :class="iconClasses"></icon>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <slot name="heading">
                                    <h3 v-if="title" class="pt-2 text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                        {{ title }}
                                    </h3>
                                </slot>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm leading-5 text-gray-500">
                                <slot/>
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-200 border border-top px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <slot name="buttons">
                          <ub-button v-if="!hideConfirm" :variant="variant" @click="confirm" class="mx-2">
                            {{ confirmLabel }}
                          </ub-button>
                          <ub-button v-if="!hideCancel" variant="secondary" outline @click="cancel" class="mx-2">
                            {{ cancelLabel }}
                          </ub-button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>

    import Icon from '@/components/ui/Icon'

    export default {

        components: {
            Icon
        },

        props: {
            variant: {
                type: String,
                default: 'success',
                validator: function (value) {
                    // The value must match one of these strings
                    return ['success', 'info', 'warning', 'danger'].indexOf(value) !== -1
                }
            },
            title: {
                type: String
            },
            confirmLabel: {
                type: String,
                default: 'Okay'
            },
            cancelLabel: {
                type: String,
                default: 'Cancel'
            },
            hideCancel: {
                type: Boolean,
                default: false
            },
            hideConfirm: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                icon: {
                    color: 'green',
                    image: 'M5 13l4 4L19 7'
                },
                successIcon: {
                    color: 'green',
                    image: 'check'
                },
                infoIcon: {
                    color: 'blue',
                    image: 'informationCircle'
                },
                warningIcon: {
                    color: 'yellow',
                    image: 'exclamation'
                },
                dangerIcon: {
                    color: 'red',
                    image: 'x'
                }
            }
        },

        computed: {
            color () {
                switch (this.type) {
                    case 'success':
                        return 'green'
                    case 'info':
                        return 'blue'
                    case 'danger':
                        return 'red'
                }
            },
            buttonClasses () {
                const classes = []

                classes.push(`bg-${this.icon.color}-600`)
                classes.push(`hover:bg-${this.icon.color}-500`)
                classes.push(`focus:border-${this.icon.color}-700`)
                classes.push(`focus:shadow-outline-${this.icon.color}`)

                return classes
            },
            iconClasses () {
                const classes = []

                classes.push(`text-${this.icon.color}-700`)

                return classes
            }
        },

        methods: {
            confirm () {
                this.$emit('confirm')
            },
            cancel () {
                this.$emit('cancel')
            }
        },

        mounted () {
            switch (this.variant) {
                case 'danger':
                    this.icon = this.dangerIcon
                    break;
                case 'info':
                    this.icon = this.infoIcon
                    break;
                case 'warning':
                    this.icon = this.warningIcon
                    break;
                default:
                    // Success will be the default
                    this.icon = this.successIcon
                    break;
            }
        }

    }

</script>
