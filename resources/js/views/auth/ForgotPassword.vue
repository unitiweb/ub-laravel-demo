<template>

    <div>
        <div class="mb-4">
            <logo size="lg" class="mx-auto"></logo>
        </div>
        <card :variant="success ? 'success' : 'primary'">
            <template v-slot:header>
                <h2 class="text-center text-3xl leading-9 font-extrabold text-gray-700">
                    Forgot Password?
                </h2>
                <p class="mt-2 text-sm leading-5 text-gray-500">
                    Enter your email address and we will send you instructions for resetting your password
                </p>
            </template>

            <div v-if="success === false" class="p-4">
                <div class="px-4 py-2">
                    <f-input label="Email"
                             type="email"
                             placeholder="Email"
                             v-model="$v.email.$model"
                             error="Email must be valid"
                             :validator="$v.email"/>
                </div>
                <alert variant="danger" :show="!!error">{{ error }}</alert>
            </div>

            <div v-if="success === true" class="p-4">
                <p>
                    If the email is tied to an account, then an email was sent with instructions to reset your password.
                </p>
                <p>
                    Once you complete the processes you will be able to login with your new password.
                </p>
            </div>

            <template v-slot:footer>
                <div v-if="success === false" class="flex gap-2">
                    <ub-button @click="login" variant="secondary" outline size="sm" block>Back to Login</ub-button>
                    <ub-button @click="send" variant="primary" size="sm" block>Send</ub-button>
                </div>
                <div v-if="success === true">
                    <ub-button @click="login" variant="success" outline size="sm" block>Login</ub-button>
                </div>
            </template>
        </card>
    </div>
</template>

<script>
    import Logo from '@/components/ui/logo/Logo'
    import {email, required} from "vuelidate/lib/validators";

    export default {

        components: {
            Logo
        },

        data () {
            return {
                email: null,
                success: false,
                error: null,
            }
        },

        validations: {
            email: {
                required,
                email
            }
        },

        methods: {
            login () {
                this.$router.push({ name: 'login' })
            },

            async send () {
                this.error = null
                this.$v.$touch()
                if (this.$v.$invalid) return;
                try {
                    this.$store.commit('loading', true);
                    await this.$http.forgotPassword(this.email)
                    this.success = true
                    this.$store.commit('loading', false);
                } catch ({ error }) {
                    console.log('error', error)
                    this.error = error.message
                    this.success = false
                    this.$store.commit('loading', false);
                }
            }
        }
    }
</script>
