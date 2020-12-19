<template>
    <button @click="toggle" class="p-2 rounded-md inline-flex items-center justify-center text-light-blue-200 hover:text-white hover:bg-light-blue-800 focus:outline-none focus:bg-light-blue-800 focus:text-white transition duration-150 ease-in-out" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <!-- Icon when menu is closed. -->
        <icon name="menu" class="block flex-shrink-0"></icon>
    </button>
</template>

<script>
    import Icon from '@/components/ui/Icon'

    export default {

        components: {
            Icon
        },

        methods: {
            toggle () {
                this.$emit('toggle')
            }
        }

    }

</script>
