<template>
    <router-link :to="{ name: to }"
                 :class="classes"
                 :active-class="activeClass">
        <slot/>
    </router-link>
</template>

<script>
    export default {
        props: {
            label: {
                type: String
            },
            to: {
                type: String
            },
            mobile: {
                type: Boolean,
                default: false
            }
        },
        data () {
            return {
                activeClass: 'bg-gray-900'
            }
        },
        computed: {
            classes () {
                if (this.mobile) {
                    return [
                        'block',
                        'px-3',
                        'py-2',
                        'rounded-md',
                        'text-base',
                        'font-medium',
                        'text-gray-300',
                        'hover:text-white',
                        'hover:bg-gray-700',
                        'focus:outline-none',
                        'focus:text-white',
                        'focus:bg-gray-700'
                    ]
                }

                return [
                    'px-3',
                    'py-2',
                    'rounded-md',
                    'text-sm',
                    'font-medium',
                    'text-gray-300',
                    'hover:text-white',
                    'hover:bg-gray-700',
                    'focus:outline-none',
                    'focus:text-white',
                    'focus:bg-gray-700'
                ]
            }
        }
    }
</script>
