<template>

    <div>
        <div v-if="user" class="mb-4">
            <form @submit.prevent="login">
                <ul class="grid grid-cols-1 gap-6">
                    <li class="col-span-1 flex flex-col text-center bg-white border border-gray-300 rounded-lg shadow-md divide-y divide-gray-200">
                        <div class="flex-1 flex flex-col p-8">
                            <avatar v-if="user" class="flex-shrink-0 mx-auto border border-gray-300 shadow-md" :filename="avatar" rounded :size="32"/>
                            <h3 class="mt-6 text-gray-900 text-lg font-medium">{{ fullName }}</h3>
                            <div>
                                <div class="px-4 py-2">
                                    <f-input type="password" label="Password" placeholder="password" v-model="credentials.password"/>
                                </div>
                                <alert variant="danger" :show="hasError">{{ errorMessage }}</alert>
                            </div>
                        </div>
                        <div>
                            <div class="-mt-px flex divide-x divide-gray-200">
                                <div class="w-0 flex-1 flex">
                                    <a href="#" @click.prevent="logout" class="relative -mr-px w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-bl-lg hover:text-gray-500">
                                        <icon name="logout" :size="5" class="text-gray-400"/>
                                        <span class="ml-3">Sign Out</span>
                                    </a>
                                </div>
                                <div class="-ml-px w-0 flex-1 flex">
                                    <a href="#" @click.prevent="login" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg hover:text-gray-500">
                                        <icon name="login" :size="5" class="text-gray-400"/>
                                        <span class="ml-3">Unlock</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</template>

<script>
    import Logo from '@/components/ui/logo/Logo'
    import Icon from '@/components/ui/Icon'
    import Avatar from '@/components/ui/Avatar'

    export default {

        components: {
            Logo,
            Icon,
            Avatar
        },

        data () {
            return {
                credentials: {
                    email: '',
                    password: ''
                },
                user: null,
                hasError: false,
                errorMessage: '',
                loading: false,
            }
        },

        computed: {
            /**
             * Get the user's full name from the store
             */
            fullName () {
                if (this.user) {
                    return `${this.user.firstName} ${this.user.lastName}`
                }
            },

            avatar () {
                if (this.user) {
                    return this.user.avatar
                }
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
            // Get the locked user
            const user = this.$store.getters.isLocked
            if (!user) {
                // If there is no email then log the user out
                location.href = this.getUrl('logout')
            }
            this.credentials.email = user.email
            this.user = user
        }
    }
</script>
