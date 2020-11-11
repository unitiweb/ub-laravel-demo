<template>
    <spinner :loading="true" label="Signing Out..."></spinner>
</template>

<script>
    import Layout from '@/components/layouts/Layout'
    import Spinner from '@/components/ui/Spinner'

    export default {

        components: {
            Layout,
            Spinner
        },

        mounted () {
            setTimeout(() => {
                this.$store.dispatch('logout')
                this.$router.push({ name: 'login', force: true })
            }, 1000)
        }

    }
</script>
