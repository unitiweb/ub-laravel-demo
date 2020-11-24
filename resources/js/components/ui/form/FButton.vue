<template>
<!--    <span :class="wrapperClasses">-->
        <button type="button" class="btn" :class="classes" @click="click">
            <icon v-if="iconLeft" :name="iconLeft" class="-ml-1 mr-2 h-5 w-5"></icon>
            <icon v-if="icon" :name="icon"  class="h-5 w-5"></icon>
            <slot v-if="!icon"/>
            <icon v-if="iconRight" :name="iconRight" class="mr-1 ml-2 h-5 w-5"></icon>
        </button>
<!--    </span>-->
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
            },
            iconLeft: {
                type: String,
                default: null
            },
            iconRight: {
                type: String,
                default: null
            }
        },

        data () {
            return {
                // wrapperBase: 'rounded-md shadow-md',
                buttonBase: 'rounded-md shadow-md border border-transparent leading-5 font-medium rounded-md focus:outline-none transition ease-in-out duration-150 px-2',
                colorMap: {
                    primary: 'blue',
                    secondary: 'gray',
                    success: 'green',
                    info: 'teal',
                    warning: 'ornage',
                    danger: 'red'
                }
            }
        },

        computed: {
            // wrapperClasses () {
            //     const classes = []
            //
            //     if (this.block) {
            //         classes.push('block w-full')
            //     } else {
            //         classes.push('inline-flex')
            //     }
            //
            //     // BAse style
            //     classes.push(this.wrapperBase)
            //
            //     return classes
            // },
            classes () {
                const classes = []

                // Make full width if button is type block
                if (this.block) {
                    classes.push('w-full flex justify-center')
                } else {
                    classes.push('inline-flex items-center')
                }

                classes.push(this.buttonBase)
                classes.push(this.getSize())
                classes.push(this.getColor())

                return classes
            }
        },

        methods: {
            click () {
                this.$emit('click')
            },
            getColor () {
                const color = this.colorMap[this.variant]

                if (this.outline) {
                    return `text-${color}-700
                            bg-${color}-100
                            border
                            border-${color}-500
                            hover:bg-${color}-200
                            hover:border-${color}-600
                            focus:border-${color}-600
                            focus:bg-${color}-300
                            focus:shadow-outline-${color}
                            active:bg-${color}-300`
                }
                return `text-white
                        bg-${color}-600
                        border-${color}-700
                        hover:bg-${color}-500
                        focus:border-${color}-700
                        focus:shadow-outline-${color}
                        active:bg-${color}-700`
            },
            getSize () {
                let size = ''

                if (this.size === 'xs') {
                    size = 'text-xs h-6 px-2 py-0'
                } else if (this.size === 'sm') {
                    size = 'text-sm h-8 px-1 py-1'
                } else if (this.size === 'md') {
                    size = 'text-base h-10 px-3 py-2'
                } else if (this.size === 'lg') {
                    size = 'text-base h-12 px-3 py-3'
                } else if (this.size === 'xl') {
                    size = 'text-2xl h-12 px-4 py-4'
                }
                return `text-${this.size} ${size}`
            }
        }

    }
</script>
