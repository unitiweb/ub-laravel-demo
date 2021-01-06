<template>
    <div>
        <div class="mb-4">
            <logo size="lg" class="mx-auto"></logo>
        </div>
        <card variant="primary">
            <template v-slot:header>
                <h2 class="text-center text-3xl leading-9 font-extrabold text-gray-500">
                    Verify Your Email
                </h2>
                <p class="mt-2 text-center text-sm leading-5 text-gray-500">
                    Your email needs to be validated.
                </p>
            </template>

            <div v-if="state === 'verify'" class="p-4 text-sm leading-5 text-gray-500">
                <p class="p-4">
                    Check your email to get your verification code.
                    Enter the code below to continue.
                </p>
                <div class="px-4 py-2">
                    <f-input label="Validation Code" placeholder="code" v-model="form.code"/>
                </div>
                <alert variant="danger" :show="!!error" title="Invalid Code" :message="error"/>
            </div>
            <div v-if="state === 'success'" class="p-4 pt-6 text-sm leading-5 text-gray-500">
                <h3 class="px-4 text-xl font-bold text-green-500">Verified!</h3>
                <p class="p-4 pt-0">
                    Your email was successfully verified. You may now login.
                </p>
            </div>

            <template v-slot:footer>
                <div v-if="state === 'verify'" class="flex gap-2">
                    <ub-button @click="verify" block size="sm" variant="primary">Validate</ub-button>
                </div>
                <div v-if="state === 'success'" class="flex gap-2">
                    <ub-button @click="login" block size="sm" class="flex-1">Go to Login</ub-button>
                </div>
            </template>

        </card>
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
                state: 'verify',
                error: null,
                form: {
                    code: ''
                }
            }
        },

        methods: {
            async verify() {
                this.error = null
                this.$store.commit('loading', true)
                try{
                    await this.$http.verifyEmail(this.form.code)
                    this.state = 'success'
                    this.$store.commit('loading', false)
                } catch ({ error }) {
                    this.$store.commit('loading', false)
                    if (error.status === 422) {
                        this.error = 'The code is not valid. Check your email and re-enter.'
                    } else {
                        console.log('error', error)
                    }
                }
            },

            login () {
                this.$router.push({ name: 'login' })
            }
        }
    }

</script>
