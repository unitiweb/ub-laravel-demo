<template>
    <a href="#" @click="click" class="group mt-1 border-l-4 px-3 py-2 flex items-center text-sm font-medium" :class="classes">
        <icon :name="icon" size="6" class="flex-shrink-0 -ml-1 mr-3" :class="iconClasses"></icon>
        <span class="truncate">
            <slot/>
        </span>
    </a>
</template>

<script>

    export default {

        props: {
            active: {
                type: Boolean,
                default: false
            },
            icon: {
                type: String
            }
        },

        computed: {
            classes () {
                if (this.active) {
                    return 'bg-blue-50 border-blue-500 text-blue-700 hover:bg-blue-50 hover:text-blue-700'
                } else {
                    return 'border-transparent text-gray-900 hover:bg-gray-50 hover:text-gray-900'
                }
            },
            iconClasses () {
                if (this.active) {
                    return 'text-blue-500 group-hover:text-blue-500'
                } else {
                    return 'text-gray-400 group-hover:text-gray-500'
                }
            }
        },

        methods: {
            click () {
                this.$emit('click')
            }
        }

    }

</script>
