<template>
    <div>
        <div class="mb-4">
            <logo size="lg" class="mx-auto"></logo>
        </div>
        <card>
            <template v-slot:header>
                <h2 class="text-center text-2xl leading-9 font-extrabold text-gray-900">
                    Processing
                </h2>
            </template>
            <div class="px-4 py-2">
                You are being logged out.
            </div>
        </card>
    </div>
</template>

<script>
    import Layout from '@/components/layouts/Layout'

    export default {

        components: {
            Layout
        },

        methods: {
            getUrl () {
                let props = this.$router.resolve({
                    name: 'login'
                })
                return props.href
            }
        },

        mounted () {
            this.$store.commit('loading', true)
            setTimeout(() => {
                this.$store.dispatch('logout')
                location.href = this.getUrl()
            }, 500)
        }

    }
</script>
