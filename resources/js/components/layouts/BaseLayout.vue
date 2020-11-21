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
                return this.$store.getters.loading
            },
            onCancel () {
                console.log('spinner cancel')
            }
        }

    }
</script>
