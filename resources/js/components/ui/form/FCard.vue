<template>
    <div class="bg-gray-200 border border-gray-300 rounded-xl shadow-md sm:p-4 ">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="pt-4 sm:pt-0 px-4 sm:px-0">
                    <h3 v-if="title" class="text-lg font-medium leading-6 text-gray-900">{{ title }}</h3>
                    <p v-if="subTitle" class="mt-1 text-sm leading-5 text-gray-600">
                        {{ subTitle }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <slot/>
                    </div>
                    <div class="px-4 py-3 bg-white border-t-2 border-gray-300 border-t rounded-b-xl text-right sm:px-6">
                        <slot name="buttons"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {

    components: {},

    props: {
        title: {
            type: String
        },
        subTitle: {
            type: String
        }
    },

    computed: {},

    methods: {}

}

</script>
