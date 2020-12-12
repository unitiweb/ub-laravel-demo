<template>
    <div>
        <div class="mb-4">
            <logo size="lg" class="mx-auto"></logo>
        </div>
        <card>
            <template v-slot:header>
                <h2 class="text-center text-3xl leading-9 font-extrabold text-gray-900">
                    Sign in to your account
                </h2>
                <p class="mt-2 text-center text-sm leading-5 text-gray-600">
                    Or
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                        start your 14-day free trial
                </a>
                </p>
            </template>

            <div class="p-4">
                <div class="px-4 py-2">
                    <f-input label="Email" placeholder="email" v-model="credentials.email"/>
                </div>
                <div class="px-4 py-2">
                    <f-input type="password" label="Password" placeholder="password" v-model="credentials.password"/>
                </div>

                <alert variant="danger" :show="!!error">{{ error }}</alert>
            </div>

            <template v-slot:footer>
                <ub-button @click="login" icon-left="login" block>Login</ub-button>
            </template>
        </card>

        <div class="text-center text-sm leading-5">
            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                Forgot your password?
            </a>
        </div>

    </div>
</template>

<script>
    import Logo from '@/components/ui/logo/Logo'

    export default {

        components: {
            Logo
        },

        data () {
            return {
                credentials: {
                    email: 'dave@unitiweb.com',
                    password: 'Testing01'
                },
                error: null
            }
        },

        methods: {
            login () {
                this.error = null;
                this.$store.commit('loading', true)
                this.$http.login(this.credentials.email, this.credentials.password)
                    .then(({ data, settings, tokens, site }) => {
                        setTimeout(() => {
                            this.$store.dispatch('login', { user: data, settings, tokens, site })
                            location.href = this.getUrl('dashboard')
                        }, 500);
                    }).catch(({ error }) => {
                        this.error = error.message;
                        this.credentials.password = ''
                        this.$store.commit('loading', false)
                    })
            },
            getUrl (name) {
                let props = this.$router.resolve({ name })
                return props.href
            }
        }

    }
</script>
