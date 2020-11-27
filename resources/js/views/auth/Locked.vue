<template>
    <section>
        <div>
            <logo size="lg" class="mx-auto"></logo>
            <h2 class="mt-6 mb-0 text-center text-3xl leading-9 font-extrabold text-gray-900">
                Dave Torres
            </h2>
            <p class="mb-4 italic text-center text-sm leading-5 text-gray-600">
                Enter your password to access
            </p>
        </div>
        <div>
<!--            <input type="hidden" name="remember" value="true">-->
            <div class="-mt-px">
                <f-input type="password" label="Password" :error="hasError" :feedback="errorMessage" placeholder="password" v-model="credentials.password"/>
            </div>

<!--            <alert variant="danger" :show="!!error">{{ error }}</alert>-->

            <div class="mt-6">
                <div class="flex">
                    <div class="flex-auto p-2">
                        <ub-button @click="logout" variant="outline" icon="logout" block>Sign Out</ub-button>
                    </div>
                    <div class="flex-auto p-2">
                        <ub-button @click="login" variant="primary" icon="login" block>Unlock</ub-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import Logo from '@/components/ui/logo/Logo'
    import Icon from '@/components/ui/Icon'

    export default {

        components: {
            Logo,
            Icon
        },

        data () {
            return {
                credentials: {
                    email: null,
                    password: 'Testing01'
                },
                hasError: false,
                errorMessage: '',
                loading: false,
            }
        },

        methods: {
            login () {
                this.hasError = false
                this.errorMessage = ''
                this.$store.commit('loading', true)
                this.$http.login(this.credentials.email, this.credentials.password)
                    .then(({data, settings, tokens, site}) => {
                        setTimeout(() => {
                            this.$store.dispatch('login', {user: data, settings, tokens, site})
                            location.href = this.getUrl('dashboard')
                        }, 500);
                    }).catch(({ error }) => {
                        this.hasError = true
                        this.errorMessage = error.message
                        this.credentials.password = ''
                        this.$store.commit('loading', false)
                    })
            },
            logout () {
                // this.$store.commit('loading', true)
                this.$router.push({ name: 'logout' })
            },
            getUrl (name) {
                let props = this.$router.resolve({ name })
                return props.href
            }
        },

        mounted () {
            // Get the locked email
            const email = this.$store.getters.isLocked
            if (!email) {
                // If there is no email then log the user out
                location.href = this.getUrl('logout')
            }
            this.credentials.email = email
        }

    }
</script>
