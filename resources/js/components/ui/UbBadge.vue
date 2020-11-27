<template>
    <span :class="classes">
        <svg v-if="dot && this.small" :class="`h-2 w-2 text-${this.color}-400`" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
        </svg>
        <svg v-else-if="dot && !this.small" :class="`h-2 w-2 text-${this.color}-400`" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
        </svg>
        <slot>
            {{ label }}
        </slot>
    </span>
</template>

<script>

    export default {

        props: {
            label: {
                type: String,
                default: 'none'
            },
            variant: {
                type: String,
                default: 'primary',
                validator: value => {
                    return ['primary', 'secondary', 'success', 'info', 'warning', 'danger'].indexOf(value) !== -1
                }
            },
            outline: {
                type: Boolean,
                default: false
            },
            rounded: {
                type: Boolean,
                default: false
            },
            small: {
                type: Boolean,
                default: false
            },
            dot: {
                type: Boolean,
                default: false
            }
        },

        computed: {

            classes () {
                const classes = ['badge']

                if (this.outline) {
                    classes.push(`badge-${this.variant}-outline`)
                } else {
                    classes.push(`badge-${this.variant}-200`)
                }

                if (this.rounded) {
                    classes.push('badge-rounded-full')
                } else {
                    classes.push('badge-rounded-md')
                }

                if (this.small) {
                    classes.push('badge-sm')
                } else {
                    classes.push('badge-md')
                }

                return classes
            }

        }

    }

</script>
