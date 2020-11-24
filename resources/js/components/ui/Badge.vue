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
                const classes = ['inline-flex items-center font-medium border uppercase shadow']

                if (this.outline) {
                    classes.push(`bg-${this.color}-100 text-${this.color}-800 border-${this.color}-300`)
                } else {
                    classes.push(`bg-${this.color}-200 text-${this.color}-800 border-${this.color}-300`)
                }

                if (this.rounded) {
                    classes.push('rounded-full')
                } else {
                    classes.push('rounded-md')
                }

                if (this.small) {
                    classes.push('text-xs px-2 mx-2 my-1')
                } else {
                    classes.push('text-sm px-3 py-1 mx-2 my-1')
                }

                return classes
            },

            color () {
                if (this.variant === 'primary') return 'blue'
                else if (this.variant === 'secondary') return 'gray'
                else if (this.variant === 'success') return 'green'
                else if (this.variant === 'info') return 'teal'
                else if (this.variant === 'warning') return 'orange'
                else if (this.variant === 'danger') return 'red'
            }

        }

    }

</script>
