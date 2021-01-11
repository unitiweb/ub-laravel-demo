<template>
    <div>
        <div class="mb-4">
            <logo size="lg" class="mx-auto"></logo>
        </div>
        <card :variant="complete ? 'success' : 'primary'">
            <template v-slot:header>
                <h2 class="text-center text-3xl leading-9 font-extrabold text-gray-900">
                    Register New User
                </h2>
                <p class="mt-2 text-center text-sm leading-5 text-gray-600">
                    Enter your details to create your account
                </p>
            </template>

            <alert :show="!!formError" variant="danger">{{ formError }}</alert>

            <div v-if="complete">
                <div class="p-4">
                    <p class="text-gray-500">
                        Your account has been created. You have been send an email validation email.
                        Please open the email and use the validation link to finalize your registration.
                    </p>
                </div>
            </div>

            <div v-else>
                <div class="p-4">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <f-input label="First Name"
                                     placeholder="first name"
                                     v-model="$v.form.firstName.$model"
                                     error="First name is required"
                                     :validator="$v.form.firstName"/>
                        </div>
                        <div>
                            <f-input label="Last Name"
                                     placeholder="last name"
                                     v-model="$v.form.lastName.$model"
                                     error="Last name is required"
                                     :validator="$v.form.lastName"/>
                        </div>
                        <div class="col-span-2">
                            <f-input label="Email"
                                     type="email"
                                     placeholder="Email"
                                     v-model="$v.form.email.$model"
                                     error="Email is required"
                                     :validator="$v.form.email"/>
                        </div>
                        <div>
                            <f-input type="password"
                                     label="Password"
                                     placeholder="password"
                                     v-model="$v.form.password.$model"
                                     :validator="$v.form.password"/>
                        </div>
                        <div>
                            <f-input type="password"
                                     label="Retype Password"
                                     placeholder="retype password"
                                     v-model="$v.form.retype.$model"
                                     :validator="$v.form.retype"/>
                        </div>
                        <div class="col-span-2">
                            <f-input label="Site Name"
                                     placeholder="site name"
                                     v-model="$v.form.site.$model"
                                     error="Site name is required"
                                     :validator="$v.form.site"/>
                        </div>
                    </div>

                </div>
            </div>
            <template v-slot:footer>
                <div v-if="complete" class="flex gap-2">
                    <ub-button @click="cancel" block size="sm" variant="success" class="flex-1">Login</ub-button>
                </div>
                <div v-else class="flex gap-2">
                    <ub-button @click="cancel" block outline size="sm" variant="secondary" class="flex-1">Cancel</ub-button>
                    <ub-button @click="register" block size="sm" class="flex-1">Create</ub-button>
                </div>
            </template>
        </card>
    </div>
</template>

<script>
    import Logo from '@/components/ui/logo/Logo'
    import { required, maxLength, email } from 'vuelidate/lib/validators'

    export default {

        components: {
            Logo
        },

        data () {
            return {
                formError: null,
                complete: false,
                form: {
                    firstName: '',
                    lastName: '',
                    email: '',
                    password: '',
                    retype: '',
                    site: '',
                }
            }
        },

        validations: {
            form: {
                firstName: {
                    required,
                    maxLength: maxLength(30)
                },
                lastName: {
                    required,
                    maxLength: maxLength(30)
                },
                email: {
                    required,
                    email
                },
                password: {
                    required
                },
                retype: {
                    required
                },
                site: {
                    required,
                    maxLength: maxLength(30)
                }
            }
        },

        methods: {

            cancel () {
                this.$router.push({ name: 'login' })
            },

            async register () {
                this.formError = null
                this.$v.$touch()
                if (this.$v.$invalid) return;
                try {
                    await this.$http.register({
                        user: {
                            email: this.form.email,
                            password: this.form.password,
                            firstName: this.form.firstName,
                            lastName: this.form.lastName
                        },
                        site: {
                            name: this.form.site
                        }
                    })
                    this.complete = true
                } catch ({ error }) {
                    console.log('error', error)
                    if (error.code === 422) {
                        this.formError = error.message
                    }
                }

            }

        }

    }

</script>
