<template>
    <router-link class="block rounded-md py-2 px-3 text-base font-medium text-white focus:outline-none focus:text-white focus:bg-light-blue-800 transition duration-150 ease-in-out"
                 active-class="bg-black bg-opacity-25"
                 @click="click"
                 :to="to">
        <slot/>
    </router-link>
</template>

<script>

export default {

    components: {},

    props: {
        to: {
            type: [String, Object]
        }
    },

    data() {
        return {}
    },

    computed: {},

    methods: {
        click () {
            this.$emit('click')
        }
    }

}

</script>
