<template>
    <transition-fade>
        <div>
            <spinner :loading="loading"></spinner>
            <router-view></router-view>
        </div>
    </transition-fade>
</template>

<script>
    import Spinner from '@/components/ui/Spinner'
    import TransitionFade from '@/components/transitions/TransitionFade'
    import { mapGetters } from 'vuex'

    export default {

        components: {
            Spinner,
            TransitionFade
        },

        computed: {
            ...mapGetters(['loading']),
        }

    }
</script>
