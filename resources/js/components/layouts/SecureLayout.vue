<template>
    <div>
        <inactive-alert></inactive-alert>
        <router-view></router-view>
    </div>
</template>

<script>
    import InactiveAlert from '@/components/layouts/InactiveAlert'

    export default {

        components: {
            InactiveAlert
        },

        mounted () {

        }

    }
</script>
