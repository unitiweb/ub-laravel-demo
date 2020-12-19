<template>
    <div class="divide-y divide-gray-200 lg:col-span-9">
        <div v-if="user">
            <div class="py-6 px-4 sm:p-6 lg:pb-8">
                <div>
                    <h2 class="text-lg leading-6 font-medium text-gray-900">Profile</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Your profile details such as your first and last name
                    </p>
                </div>
                <div class="mt-6 grid grid-cols-12 gap-6">
                    <div class="col-span-12 sm:col-span-6">
                        <f-input label="First name"
                                 v-model="$v.form.firstName.$model"
                                 :disabled="disabled"
                                 error="First name must be between 2 and 30 characters"
                                 :validator="$v.form.firstName">
                        </f-input>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <f-input label="Last name"
                                 v-model="$v.form.lastName.$model"
                                 :disabled="disabled"
                                 error="Last name must be between 2 and 30 characters"
                                 :validator="$v.form.lastName">
                        </f-input>
                    </div>
                </div>
                <alert :show="formError" :message="formError" icon variant="danger"/>
            </div>
            <div class="flex justify-between bg-gray-100 mt-4 py-4 px-4 sm:px-6">
                <div class="text-green-500 pt-2"><span v-if="saved">saved</span></div>
                <div>
                    <ub-button v-if="disabled" @click="edit" variant="secondary" class="mx-2" outline>Edit</ub-button>
                    <ub-button v-if="!disabled" @click="cancel" variant="secondary" outline class="mx-2">Cancel</ub-button>
                    <ub-button v-if="!disabled && !$v.$anyError" @click="save" variant="primary" class="mx-2">Save</ub-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {required, minLength, maxLength} from 'vuelidate/lib/validators'

    export default {

        data () {
            return {
                formError: null,
                disabled: true,
                saved: false,
                form: {
                    firstName: null,
                    lastName: null
                }
            }
        },

        computed: {
            user () {
                return this.$store.getters.user
            }
        },

        validations: {
            form: {
                firstName: {
                    required,
                    minLength: minLength(2),
                    maxLength: maxLength(30)
                },
                lastName: {
                    required,
                    minLength: minLength(2),
                    maxLength: maxLength(30)
                }
            }
        },

        methods: {

            state (state) {
                switch (state) {
                    case 'validating':
                        this.$v.$touch()
                        break;
                    case 'reset':
                        this.formError = null
                        this.disabled = true
                        this.form.firstName = this.user.firstName
                        this.form.lastName = this.user.lastName
                        this.$v.$reset()
                        break
                    case 'edit':
                        this.disabled = false
                        break
                    case 'saved':
                        this.saved = true
                        this.disabled = true
                        this.form.email = this.user.email
                        this.$v.$reset()
                        break
                    case 'error':
                        break
                }
            },

            async save () {
                try {
                    this.state('validating')
                    if (this.$v.$invalid) return;
                    this.formError = null
                    await this.$http.updateProfile(this.form)
                    await this.$store.dispatch('user', this.form)
                    this.state('saved')
                    setTimeout(() => {
                        this.saved = false
                    }, 3000)
                } catch (error) {
                    console.log('error', error)
                    if (error.code === 422) {
                        this.state('error')
                        this.formError = error.message
                    }
                }
            },

            edit () {
                this.state('edit')
            },

            cancel () {
                this.state('reset')
            },

            resetForm () {
                this.state('reset')
            }

        },

        mounted () {
            this.resetForm()
        }

    }

</script>
