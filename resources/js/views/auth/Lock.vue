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
                We are locking your login. All you'll need is your password to log back in.
            </div>
        </card>
    </div>
</template>

<script>
    import Logo from '@/components/ui/logo/Logo'

    export default {

        components: {
            Logo
        },

        methods: {
            getUrl () {
                let props = this.$router.resolve({
                    name: 'locked'
                })
                return props.href
            }
        },

        mounted () {
            this.$store.commit('loading', true)
            setTimeout(() => {
                this.$store.dispatch('lock')
                location.href = this.getUrl()
            }, 500)
        }

    }
</script>
