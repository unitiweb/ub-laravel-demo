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
            <input type="hidden" name="remember" value="true">
            <div class="rounded-md shadow-sm">
                <div class="-mt-px">
                    <form-input name="password" placeholder="password" value="dave@unitiweb.com"/>
                </div>
            </div>
            <div class="mt-6">
                <div class="flex">
                    <div class="flex-auto p-2">
                        <form-button @click="logout" size="xl" outline icon="logout" block>
                            Sign Out
                        </form-button>
                    </div>
                    <div class="flex-auto p-2">
                        <form-button @click="login" size="xl" icon="login" block>
                            Unlock
                        </form-button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import Logo from '@/components/ui/logo/Logo'
    import FormButton from '@/components/ui/form/FormButton'
    import FormInput from '@/components/ui/form/FormInput'
    import Icon from '@/components/ui/Icon'

    export default {

        components: {
            Logo,
            Icon,
            FormButton,
            FormInput
        },

        data () {
            return {
                credentials: {
                    email: 'dave@unitiweb.com',
                    password: 'Testing01'
                },
                error: null,
                loading: false,
            }
        },

        methods: {
            login () {
                this.loading = true
                this.$router.push({ name: 'logout' })
            },
            logout () {
                this.loading = true
                this.$router.replace({ name: 'logout' })
            }
        }

    }
</script>
