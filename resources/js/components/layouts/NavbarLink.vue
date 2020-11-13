<template>
    <router-link :to="to"
                 @click="click"
                 aria-current="page"
                 active-class="bg-black bg-opacity-25 focus:outline-none focus:bg-light-blue-800"
                 class="rounded-md
                        py-2
                        px-3
                        text-sm
                        text-white
                        focus:outline-none
                        focus:bg-light-blue-800
                        transition
                        duration-150
                        ease-in-out
                        hover:bg-light-blue-800
                        focus:outline-none
                        focus:bg-light-blue-800
                ">
        <slot/>
    </router-link>
</template>

<script>
    export default {

        props: {
            to: {
                type: [Object, String]
            }
        },

        methods: {
            click () {
                this.$emit('click')
            }
        }

    }
</script>
