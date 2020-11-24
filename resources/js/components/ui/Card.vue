<template>
    <div :class="wrapperClasses">
        <div :class="headerClasses">
            <slot name="header">
                {{ header }}
            </slot>
        </div>
        <div :classes="classes">
            <slot/>
        </div>
        <div :class="footerClasses">
            <slot name="footer">
                {{ footer }}
            </slot>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            header: {
                type: String
            },
            footer: {
                type: String
            },
            variant: {
                type: String,
                default: 'secondary',
                validator: value => {
                    return ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'outline'].indexOf(value) !== -1
                }
            }
        },

        data () {
            return {
                border: '300'
            }
        },

        computed: {

            wrapperClasses () {
                const classes = ['rounded-lg shadow-xl m-2 border-2 mx-auto']

                classes.push(`border-${this.color}-${this.border}`)

                return classes
            },

            headerClasses () {
                const classes = ['text-lg font-weight-bold p-4 border-b rounded-t']

                classes.push(`bg-${this.color}-200 border-${this.color}-${this.border}`)

                return classes
            },

            classes () {
                const classes = ['bg-white rounded-md text-gray-700 p-4']

                classes.push(`text-${this.color}-700 border-${this.color}-${this.border}`)

                return classes
            },

            footerClasses () {
                const classes = []

                classes.push(`p-4 border-t bg-gray-100 rounded-b-lg border-${this.color}-${this.border}`)

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
