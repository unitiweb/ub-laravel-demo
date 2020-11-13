<template>
    <t-button @click="click"
              :variant="variant"
              :class="classes"
              type="button">
            <icon v-if="icon" :name="icon" :variant="variant" :class="iconClasses"></icon>
        <slot/>
    </t-button>
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
            variant: {
                type: String,
                default: 'primary',
                validator: value => {
                    return ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'outline'].indexOf(value) !== -1
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
            }
        },

        computed: {
            classes () {
                const classes = []
                classes.push(this.getSize())
                if (this.block) {
                    classes.push('w-full')
                }
                return classes
            },
            iconClasses () {
                if (this.variant === 'outline') {
                    return 'text-sx text-gray-600 ml-1 mr-2 h-5 w-5'
                }
                return 'text-white ml-1 mr-1 h-5 w-5'
            }
        },

        methods: {
            click () {
                this.$emit('click')
            },
            getSize () {
                let size = ''
                if (this.size === 'xs') {
                    size = 'px-2 py-0'
                } else if (this.size === 'sm') {
                    size = 'px-2 py-2'
                } else if (this.size === 'md') {
                    size = 'px-3 py-2'
                } else if (this.size === 'lg') {
                    size = 'px-3 py-2'
                } else if (this.size === 'xl') {
                    size = 'px-4 py-3'
                }
                return `text-${this.size} ${size}`
            }
        }

    }
</script>
