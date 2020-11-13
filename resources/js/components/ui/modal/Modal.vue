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
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                 :class="`bg-${icon.color}-200`">
                                <!-- Heroicon name: exclamation -->
                                <icon :name="type"></icon>
<!--                                <svg :class="`h-6 w-6 text-${icon.color}-600`"-->
<!--                                     xmlns="http://www.w3.org/2000/svg"-->
<!--                                     fill="none"-->
<!--                                     viewBox="0 0 24     24"-->
<!--                                     stroke="currentColor">-->
<!--                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="icon.image" />-->
<!--                                </svg>-->
                            </div>

                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <slot name="heading">
                                    <h3 v-if="title" class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                        {{ title }}
                                    </h3>
                                </slot>
                                <div class="mt-2">
                                    <p class="text-sm leading-5 text-gray-500">
                                        <slot/>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-200 border border-top px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <slot name="buttons">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                              <button v-if="!hideConfirm"
                                      @click="confirm"
                                      type="button"
                                      class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 text-base leading-6 font-medium text-white shadow-sm focus:outline-none transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                      :class="buttonClasses">
                                {{ confirmLabel }}
                              </button>
                            </span>
                            <span v-if="!hideCancel" class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                              <button @click="cancel" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                {{ cancelLabel }}
                              </button>
                            </span>
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
            type: {
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
                    image: 'M5 13l4 4L19 7'
                },
                infoIcon: {
                    color: 'blue',
                    image: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                },
                warningIcon: {
                    color: 'orange',
                    image: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                },
                dangerIcon: {
                    color: 'red',
                    image: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
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
                const color = this.icon.color
                return [
                    `bg-${color}-600`,
                    `hover:bg-${color}-500`,
                    `focus:border-${color}-700`,
                    `focus:shadow-outline-${color}`
                ]
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
            switch (this.type) {
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
