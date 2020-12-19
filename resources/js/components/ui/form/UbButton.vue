<template>
    <button type="button" class="btn" :class="classes" @click="click" @blur="blur" :disabled="disabled">
        <icon v-if="iconLeft" :name="iconLeft" class="btn-icon-left"></icon>
        <icon v-if="icon" :name="icon"></icon>
        <slot v-if="!icon"/>
        <icon v-if="iconRight" :name="iconRight" class="btn-icon-right"></icon>
    </button>
</template>

<script>
    import Icon from "@/components/ui/Icon";

    export default {

        components: {
            Icon
        },

        props: {
            outline: {
                type: Boolean,
                default: false
            },
            block: {
                type: Boolean,
                default: false
            },
            wide: {
                type: Boolean,
                default: false
            },
            variant: {
                type: String,
                default: 'primary',
                validator: value => {
                    return ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'link'].indexOf(value) !== -1
                }
            },
            size: {
                type: String,
                default: 'md',
                validator: value => {
                    return ['xs', 'sm', 'md', 'lg', 'xl'].indexOf(value) !== -1
                }
            },
            icon: {
                type: String,
                default: null
            },
            iconLeft: {
                type: String,
                default: null
            },
            iconRight: {
                type: String,
                default: null
            },
            disabled: {
                type: Boolean,
                default: false
            }
        },

        data () {
            return {
                // wrapperBase: 'rounded-md shadow-md',
                buttonBase: '',
                colorMap: {
                    primary: 'blue',
                    secondary: 'gray',
                    success: 'green',
                    info: 'teal',
                    warning: 'yellow',
                    danger: 'red'
                }
            }
        },

        computed: {
            classes () {
                const classes = ['btn']

                if (this.block) {
                    classes.push('btn-width-full')
                } else {
                    classes.push('btn-width')
                }

                if (this.outline) {
                    classes.push(`btn-${this.variant}-outline`)
                } else {
                    classes.push(`btn-${this.variant}`)
                }

                classes.push(`btn-${this.size}`)

                return classes
            }
        },

        methods: {
            click (event) {
                this.$emit('click')
            },
            blur () {
                this.$emit('blur')
            }
        }

    }
</script>
