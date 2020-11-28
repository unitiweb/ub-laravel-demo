<template>
    <transition :enter-active-class="enterStart"
                :enter-class="enterFrom"
                :enter-to-class="enterTo"
                :leave-active-class="leaveStart"
                :leave-class="leaveFrom"
                :leave-to-class="leaveTo">
        <slot/>
    </transition>
</template>

<script>

    export default {

        props: {
            out: {
                type: Boolean,
                default: false
            },
            speed: {
                type: Number,
                default: 500
            }
        },

        computed: {
            enterStart () {
                return `order-first transform transition ease-in-out duration-${this.speed}`
            },
            enterFrom () {
                if (this.out) {
                    return 'translate-x-full'
                }
                return 'translate-x-full'
            },
            enterTo () {
                if (this.out) {
                    return '-translate-x-0'
                }
                return 'translate-x-0'
            },
            leaveStart () {
                return `order-last transform transition ease-in-out duration-0`
            },
            leaveFrom () {
                if (this.out) {
                    return '-translate-x-0'
                }
                return 'translate-x-0'
            },
            leaveTo () {
                if (this.out) {
                    return '-translate-x-full'
                }
                return 'translate-x-full'
            }
        }

    }

</script>
