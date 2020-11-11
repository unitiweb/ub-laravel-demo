<template>
    <button @click="click"
            type="button"
            :class="classes">
            <icon v-if="icon" :name="icon" :class="iconClasses"></icon>
        <slot/>
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
            type: {
                type: String,
                default: 'primary',
                validator: value => {
                    return ['primary', 'secondary', 'success', 'info', 'warning', 'danger'].indexOf(value) !== -1
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
                const classes = ['btn']

                if (this.outline) {
                    classes.push('btn-' + this.type + '-outline')
                } else {
                    classes.push('btn-' + this.type)
                }

                classes.push('btn-' + this.size)

                if (this.block) {
                    classes.push('btn-full')
                }

                return classes
            },
            iconClasses () {
                if (this.outline) {
                    return `btn-${this.type}-icon-outline`
                }
                return 'btn-icon'
            }
        },

        methods: {
            click () {
                this.$emit('click')
            }
        }

    }
</script>
