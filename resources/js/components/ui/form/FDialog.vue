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
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all my-8 sm:align-middle sm:max-w-2xl w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                    <div class="px-4 py-5 p-6">
                        <h3 v-if="title" class="text-lg leading-6 font-medium text-gray-900">
                            {{ title }}
                        </h3>
                        <h5 v-if="subTitle" class="text-sm text-gray-600">
                            {{ subTitle }}
                        </h5>
                        <div class="mt-2 max-w-xl text-sm leading-5 text-gray-500">

                            <slot/>

                        </div>
                        <div class="text-right mt-5">
                            <slot name="buttons"></slot>

                        </div>
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
        title: {
            type: String
        },
        subTitle: {
            type: String
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
