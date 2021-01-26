<template>
    <layout>
        <inactive-alert></inactive-alert>
        <router-view></router-view>
    </layout>
</template>

<script>
    import Layout from '@/components/layouts/Layout'
    import InactiveAlert from '@/components/layouts/InactiveAlert'

    export default {
        components: {
            Layout,
            InactiveAlert
        }
    }
</script>
