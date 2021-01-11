<template>

    <div>
        <div class="mb-4">
<!--            <div class="hidden sm:inline form-width mx-auto my-auto pt-2">-->
<!--                <img :src="avatar" :alt="fullName" class="border border-gray-300 rounded-full shadow-lg"/>-->
<!--            </div>-->
            <logo size="lg" class="mx-auto"></logo>
        </div>
        <card>
            <template v-slot:header>
                <h2 class="text-center text-3xl leading-9 font-extrabold text-gray-700">
                    Account Locked
                </h2>
                <p class="mt-2 text-center text-sm leading-5 text-gray-500">
                    enter your password to unlock
                </p>
            </template>

            <div class="p-4">
                <div class="px-4 py-2">
                    <f-input type="password" label="Password" placeholder="password" v-model="credentials.password"/>
                </div>
                <alert variant="danger" :show="hasError">{{ errorMessage }}</alert>
            </div>

            <template v-slot:footer>
                <div class="flex gap-2">
                    <ub-button @click="logout" variant="secondary" outline size="sm" block>Sign Out</ub-button>
                    <ub-button @click="login" variant="primary" size="sm" block>Unlock</ub-button>
                </div>
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
    import Icon from '@/components/ui/Icon'

    export default {

        components: {
            Logo,
            Icon
        },

        data () {
            return {
                credentials: {
                    email: '',
                    password: ''
                },
                hasError: false,
                errorMessage: '',
                loading: false,
            }
        },

        computed: {

            /**
             * Get the user's avatar url from the store
             */
            avatar () {
                return this.$store.getters.avatar
            },

            /**
             * Get the user's full name from the store
             */
            fullName () {
                const user = this.$store.getters.user
                return `${user.firstName} ${user.lastName}`
            }

        },

        methods: {
            login () {
                this.hasError = false
                this.errorMessage = ''
                this.$store.commit('loading', true)
                this.$http.login(this.credentials.email, this.credentials.password)
                    .then(({data, tokens }) => {
                        setTimeout(() => {
                            this.$store.dispatch('login', { data, tokens })
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
