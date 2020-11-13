<template>
    <transition name="fade" mode="out-in">
        <div>
            <spinner :loading="isLoading"></spinner>
            <router-view></router-view>
        </div>
    </transition>
</template>

<script>
    import Spinner from '@/components/ui/Spinner'

    export default {

        components: {
            Spinner
        },

        computed: {
            isLoading () {
                console.log('this.$store.getters.loading', this.$store.getters.loading)
                return this.$store.getters.loading
            },
            onCancel () {
                console.log('spinner cancel')
            }
        }

    }
</script>
