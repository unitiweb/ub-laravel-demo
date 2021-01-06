<template>
    <div class="divide-y divide-gray-200 lg:col-span-9">
        <div v-if="user" class="p-4">
            <div class="py-6 px-4 sm:p-6 lg:pb-8">
                <div>
                    <h2 class="text-lg leading-6 font-medium text-gray-900">Avatar</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Select your avatar. You'll be able to resize and modify your image before uploading.
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:hidden form-width mx-auto my-auto pb-2">
                    <div class="text-center mb-6 text-lg font-bold">Current Avatar</div>
                    <img :src="avatar" :alt="fullName" class="mx-auto border border-gray-300 rounded-full shadow-lg" style="width:100px;"/>
                </div>
                <div class="form-width mx-auto pb-2 bg-gray-200 border border-gray-300 rounded shadow-lg">
                    <div class="p-1">
                        <croppa v-model="myCroppa"
                                :width="300"
                                :height="300"
                                :prevent-white-space="true"
                                :show-remove-button="false"
                                :zoom-speed="7"
                                initial-size="cover"
                                initial-position="center"
                                :disable-scroll-to-zoom="true"
                                class=""
                                @new-image="imageAdded"
                                @image-remove="imageRemoved">
                        </croppa>
                    </div>
                    <div class="form-width grid gap-x-2 gap-y-2 grid-cols-1 px-2 pb-1">
                        <input class="rounded-lg overflow-hidden appearance-none bg-gray-400 h-3 w-128" type="range" min="1" max="100" step="1" v-model="sliderValue" />
                    </div>
                    <div class="form-width grid gap-x-2 gap-y-2 grid-cols-4 px-2 py-1">
                        <ub-button @click="myCroppa.remove()" variant="danger" icon="x" block outline :disabled="disabled"></ub-button>
                        <ub-button @click="myCroppa.rotate(-1)" variant="primary" icon="reply" block outline :disabled="disabled"></ub-button>
                        <ub-button @click="myCroppa.rotate(1)" variant="primary" icon="reply" block outline :disabled="disabled"></ub-button>
                        <ub-button @click="myCroppa.chooseFile()" variant="success" icon="selector" block outline :disabled="disabled"></ub-button>
                    </div>
                    <div class="form-width px-2 py-1">
                        <ub-button @click="uploadCroppedImage" variant="primary" icon-right="upload" block :disabled="disabled">Upload Avatar</ub-button>
                    </div>
                </div>
                <div class="hidden sm:inline form-width mx-auto my-auto pt-2">
                    <div class="text-center mb-6 text-lg font-bold">Current Avatar</div>
                    <img :src="avatar" :alt="fullName" class="border border-gray-300 rounded-full shadow-lg"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        props: {
            user: {
                type: Object
            }
        },

        data () {
            return {
                myCroppa: {},
                sliderValue: 0,
                disabled: false
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

        watch: {
            /**
             * Watch the slider value so the zoom can be updated when the slider is moved
             */
            sliderValue (newValue, oldValue) {
                if (newValue > oldValue) {
                    this.myCroppa.zoomIn()
                } else if (newValue < oldValue) {
                    this.myCroppa.zoomOut()
                }
            }
        },

        methods: {

            /**
             * Called when the image is added to croppa
             */
            imageAdded () {
                this.sliderValue = 0
                this.disabled = false
            },

            /**
             * Called when the image is removed from croppa
             */
            imageRemoved () {
                this.sliderValue = 0
                this.disabled = true
            },

            /**
             * Called when the image is to be sent to the api
             */
            async uploadCroppedImage () {
                this.$store.commit('loading', true)
                this.disabled = true
                const image = await this.myCroppa.generateDataUrl('image/png', 0.8)
                this.$http.uploadAvatar(image).then(({ data }) => {
                    this.$store.commit('loading', false)
                    this.disabled = false
                    this.$store.commit('avatar', data.avatar)
                    this.myCroppa.remove()
                }).catch(({ error }) => {
                    this.error = error
                    this.$store.commit('loading', false)
                    this.disabled = false
                })
            }

        }

    }

</script>

<style lang="scss" scoped>

    .form-width {
        width: 315px;
    }

</style>
