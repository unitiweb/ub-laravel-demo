<template>
    <loading :active="loading"
             :can-cancel="false"
             :opacity="opacity"
             blur="3px"
             :is-full-page="isFullPage">
    </loading>
</template>

<script>
    import Loading from 'vue-loading-overlay'
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {

        components: {
            Loading
        },

        props: {
            loading: {
                type: Boolean,
                default: false
            },
            isFullPage: {
                type: Boolean,
                default: false
            },
            opacity: {
                type: Number,
                default: 0.5
            }
        }

    }
</script>
