<template>

    <div>
        <div class="mb-4">
            <logo size="lg" class="mx-auto"></logo>
        </div>
        <card :variant="state === 'success' ? 'success' : 'primary'">
            <template v-slot:header>
                <h2 class="text-center text-3xl leading-9 font-extrabold text-gray-700">
                    Change your Password?
                </h2>
                <p class="mt-2 text-sm leading-5 text-gray-500">
                    You were given a code in an email. Use that code below, then enter your new password.
                </p>
            </template>

            <div v-if="state === 'auth'" class="p-4">
                <div class="px-4 py-2">
                    <f-input label="Email" placeholder="email" v-model="form.email"/>
                </div>
                <div class="px-4 py-2">
                    <f-input label="Code" placeholder="code" v-model="form.code"/>
                </div>
                <alert variant="danger" :show="!!error">{{ error }}</alert>
            </div>

            <div v-if="state === 'validated'" class="p-4">
                <div class="px-4 py-2">
                    <f-input type="password" label="New Password" placeholder="password" v-model="form.password"/>
                </div>
                <div class="px-4 py-2">
                    <f-input type="password" label="Retype Password" placeholder="retype" v-model="form.retype"/>
                </div>
                <alert variant="danger" :show="!!error">{{ error }}</alert>
            </div>

            <div v-if="state === 'success'" class="p-4">
                <p>
                    You're password has been reset. You may now login with your new password.
                </p>
            </div>

            <div v-if="state === 'other'" class="p-4">
                <p>
                    If the email is tied to an account, then an email was sent with instructions to reset your password.
                </p>
                <p>
                    Once you complete the processes you will be able to login with your new password.
                </p>
            </div>

            <template v-slot:footer>
                <div v-if="state === 'auth'" class="flex gap-2">
                    <ub-button @click="cancel" variant="secondary" outline size="sm" block>Cancel</ub-button>
                    <ub-button @click="validateCode" variant="primary" size="sm" block>Next</ub-button>
                </div>
                <div v-if="state === 'validated'" class="flex gap-2">
                    <ub-button @click="cancel" variant="secondary" outline size="sm" block>Cancel</ub-button>
                    <ub-button @click="reset" variant="secondary" outline size="sm" block>Reset Password</ub-button>
                </div>
                <div v-if="state === 'success'">
                    <ub-button @click="cancel" variant="secondary" outline size="sm" block>Login</ub-button>
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
                form: {
                    code: '',
                    email: '',
                    password: '',
                    retype: '',
                    resetToken: '',
                },
                state: 'auth',
                error: null,
            }
        },

        // validations: {
        //     form: {
        //         email: {
        //             required
        //         }
        //         code:
        //         required,
        //         email
        //     }
        // },

        methods: {
            cancel () {
                this.$router.push({ name: 'login' })
            },

            async validateCode () {
                try {
                    const { data } = await this.$http.forgotPasswordValidate(this.form.email, this.form.code)
                    this.form.resetToken = data.token
                    this.state = 'validated'
                } catch ({ error }) {
                    console.log('error', error)
                }
            },

            async reset () {
                try {
                    const { data } = await this.$http.forgotPasswordReset(
                        this.form.email,
                        this.form.password,
                        this.form.resetToken
                    )
                    this.state = 'success'
                    this.form.code = ''
                    this.form.email = ''
                    this.form.resetToken = ''
                } catch ({ error }) {
                    console.log('error', error)
                }
            }
        }
    }
</script>
