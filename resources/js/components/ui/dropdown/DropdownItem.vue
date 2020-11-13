<template>
    <a href="#" @click="click" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition ease-in-out duration-150" role="menuitem">
        <slot/>
    </a>
</template>

<script>
    export default {

        methods: {
            click () {
                this.$emit('click')
            }
        }

    }
</script>
