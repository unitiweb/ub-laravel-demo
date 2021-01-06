<template>
    <transition-fade>
        <div>
            <spinner :loading="isLoading"></spinner>
            <router-view></router-view>
        </div>
    </transition-fade>
</template>

<script>
    import Spinner from '@/components/ui/Spinner'
    import TransitionFade from '@/components/transitions/TransitionFade'

    export default {

        components: {
            Spinner,
            TransitionFade
        },

        computed: {
            isLoading () {
                return this.$store.getters.loading
            },
            onCancel () {
                //...
            }
        }

    }
</script>
