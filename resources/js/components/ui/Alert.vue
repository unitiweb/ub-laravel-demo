<template>
    <div v-if="show" :class="classes.wrapper">
        <div class="flex">
            <div v-if="icon" class="flex-shrink-0">
                <icon :name="getIcon" size="5" :class="classes.icon"></icon>
            </div>
            <div class="ml-3">
                <h3 v-if="title" :class="classes.title">
                    {{ title }}
                </h3>
                <div :class="classes.body">
                    <p>
                        <slot>{{ message }}</slot>
                    </p>
                </div>
                <div v-if="links.length > 0" class="mt-4">
                    <div class="-mx-2 -my-1.5 flex">
                        <ub-button v-for="(link, index) in links" :key="`link-button-${index}`" @click="link.click" size="sm" variant="link">
                            {{ link.label }}
                        </ub-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {

    props: {
        show: {
            type: [String, Boolean],
            default: false
        },
        title: {
            type: String
        },
        message: {
            type: String
        },
        /**
         * An example of a link
         *
         * const link = [
         *     { label: 'Status', click: this.alertClick },
         *     { label: 'Dismiss', click: this.alertClick }
         * ]
         *
         */
        links: {
            type: Array,
            default: () => []
        },
        variant: {
            type: String,
            default: 'clean',
            validator: value => {
                return ['success', 'info', 'warning', 'danger', 'clean'].indexOf(value) !== -1
            }
        },
        icon: {
            type: Boolean,
            default: false
        },
        dismissible: {
            type: Boolean,
            default: false
        },
        timeout: {
            type: Number
        }
    },

    computed: {
        getTimeout () {
            if (this.timeout) {
                return this.timeout * 1000
            }
        },
        getColor () {
            switch (this.variant) {
                case 'success':
                    return 'green'
                case 'info':
                    return 'light-blue'
                case 'warning':
                    return 'yellow'
                case 'danger':
                    return 'red'
                default:
                    return 'gray'
            }
        },
        getIcon () {
            switch (this.variant) {
                case 'success':
                    return 'checkCircle'
                case 'info':
                    return 'informationCircle'
                case 'warning':
                    return 'exclamationCircle'
                case 'danger':
                    return 'xCircle'
                default:
                    return 'checkCircle'
            }
        },
        classes () {
            // Base styles
            const classes = {
                wrapper: [`bg-${this.getColor}-50 border border-l-8 border-${this.getColor}-300 shadow-md rounded-md m-4 p-4`],
                icon: [`h-5 w-5 text-${this.getColor}-400`],
                title: [`mb-1 text-sm font-medium text-${this.getColor}-800`],
                body: [`text-sm text-${this.getColor}-700`],
                close: ['ml-4 rounded']
            }

            if (!this.icon && !this.title) {
                classes.body.push('')
            } else if (!this.icon) {
                classes.body.push('mt-2')
            }

            return classes
        }
    }

}

</script>
