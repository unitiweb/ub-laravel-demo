<template>
    <div>
        <div class="mb-4">
            <logo size="lg" class="mx-auto"></logo>
        </div>
        <card variant="primary">
            <template v-slot:header>
                <h2 class="text-center text-3xl leading-9 font-extrabold text-gray-500">
                    Sign in to your account
                </h2>
                <p class="mt-2 text-center text-sm leading-5 text-gray-500">
                    enter your credentials or register to gain access
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
                <div class="flex gap-2">
                    <ub-button @click="register" block outline size="sm" variant="secondary" class="flex-1">New User</ub-button>
                    <ub-button @click="login" block size="sm" class="flex-1">Login</ub-button>
                </div>
            </template>
        </card>
        <div class="text-center text-sm text-blue-700 mt-4">
            <router-link :to="{ name: 'forgot-password' }">forgot password</router-link>
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
                    email: '',
                    password: ''
                },
                error: null
            }
        },

        methods: {
            register () {
                this.$router.push({ name: 'register' })
            },
            login () {
                this.error = null;
                this.$store.commit('loading', true)
                this.$http.login(this.credentials.email, this.credentials.password)
                    .then(({ data, tokens }) => {
                        setTimeout(() => {
                            this.$store.dispatch('login', { data, tokens })
                            location.href = this.getUrl('dashboard')
                        }, 500);
                    }).catch(({ error }) => {
                        if (error.exception === 'UserNotVerifiedException') {
                            this.$router.push({name: 'verify-email'})
                        } else {
                            this.error = error.message;
                            this.credentials.password = ''
                        }
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
