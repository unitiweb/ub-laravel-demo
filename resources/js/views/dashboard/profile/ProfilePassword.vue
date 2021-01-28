<template>
    <div class="divide-y divide-gray-200 lg:col-span-9">
        <div v-if="user">
            <div class="py-6 px-4 sm:p-6 lg:pb-8">
                <div>
                    <h2 class="text-lg leading-6 font-medium text-gray-900">Password</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        You may change your password here. Once you initiate the password change you will be able to login in with your new password.
                    </p>
                </div>
                <div class="mt-6 px-6 grid grid-cols-12 gap-6">
                    <div class="col-span-12 sm:col-span-12">
                        <f-input type="password"
                                 label="Original Password"
                                 v-model="$v.form.original.$model"
                                 error="Original password is required"
                                 :enable-success="false"
                                 :validator="$v.form.original"/>
                    </div>
                    <div class="col-span-12 sm:col-span-12">
                        <f-input type="password"
                                 label="New Password"
                                 v-model="$v.form.password.$model"
                                 error="Password is required"
                                 :enable-success="false"
                                 :validator="$v.form.password"/>
                    </div>
                    <div class="col-span-12 sm:col-span-12">
                        <f-input type="password"
                                 label="Retype Password"
                                 v-model="$v.form.retype.$model"
                                 error="Passwords must match"
                                 :enable-success="false"
                                 :validator="$v.form.retype"/>
                    </div>
                </div>
            </div>
            <alert :show="!!formError" :message="formError" icon variant="danger"/>
            <alert :show="success" message="Your password has been successfully changed." icon variant="success"/>
            <div class="flex justify-between bg-gray-100 mt-4 py-4 px-4 sm:px-6">
                <div class="text-green-500 pt-2"><span v-if="success">changed</span></div>
                <div>
                    <ub-button @click="cancel" variant="secondary" outline class="mx-2">Cancel</ub-button>
                    <ub-button @click="change" variant="primary" class="mx-2">Change</ub-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import { required } from "vuelidate/lib/validators";

    export default {

        data () {
            return {
                formError: null,
                success: false,
                form: {
                    original: null,
                    password: null,
                    retype: null
                }
            }
        },

        validations: {
            form: {
                original: {
                    required
                },
                password: {
                    required
                },
                retype: {
                    required
                }
            }
        },

        computed: {
            user () {
                return this.$store.getters.user
            }
        },

        methods: {

            async change () {
                this.$v.$touch()
                if (this.$v.$invalid) return;
                this.formError = null
                this.success = false
                try {
                    if (this.form.original === this.form.password) {
                        this.formError = 'The new password can not match the original'
                    } else if (this.form.password !== this.form.retype) {
                        this.formError = 'The new password and retype do not match'
                    } else {
                        await this.$http.updatePassword({
                            original: this.form.original,
                            password: this.form.password
                        })
                        this.success = true
                        this.resetForm()
                    }
                } catch ({ error }) {
                    this.resetForm()
                    if (error.code === 422) {
                        this.formError = error.message
                    } else {
                        console.log('error', error)
                    }
                }

            },

            resetForm () {
                this.form.original = null
                this.form.password = null
                this.form.retype = null
                this.$v.$reset()
            },

            cancel () {
                this.resetForm()
            }

        }

    }

</script>
