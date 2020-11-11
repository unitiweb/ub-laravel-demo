<template>



    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <label v-if="label" :for="name" class="block pl-3 text-sm font-medium leading-5 text-gray-700">{{ label }}</label>
            <input :id="name" :name="name" class="form-input" :placeholder="placeholder" value="dave@unitiweb.com">
        </div>
    </div>





<!--    <div class="flex flex-col space-y-6 lg:flex-row lg:space-y-0 lg:space-x-6">-->
<!--        <div class="flex-grow space-y-6">-->
<!--            <div class="space-y-1">-->
<!--                <label for="username" class="block text-sm font-medium leading-5 text-gray-700">-->
<!--                    Username-->
<!--                </label>-->
<!--                <div class="rounded-md shadow-sm flex">-->
<!--                    <span class="bg-gray-50 border border-r-0 border-gray-300 rounded-l-md px-3 inline-flex items-center text-gray-500 sm:text-sm">-->
<!--                        workcation.com/-->
<!--                    </span>-->
<!--                    <input id="username" class="form-input flex-grow block w-full min-w-0 rounded-none rounded-r-md transition duration-150 ease-in-out sm:text-sm sm:leading-5" value="lisamarie">-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

<!--    <div class="-->
<!--            text-gray-700-->
<!--            mt-1-->
<!--            p-0-->
<!--            block-->
<!--            w-full-->
<!--            border-2-->
<!--            border-gray-400-->
<!--            rounded-md-->
<!--            shadow-md">-->
<!--        <input id="first_name"-->
<!--               class="-->
<!--                    form-input-->
<!--                    w-full m-0 py-2 px-4 bg-transparent-->

<!--                    focus:border-transparent-->
<!--                    focus:border-->
<!--                    focus:border-red-100-->
<!--                    focus:border-opacity-50-->

<!--                    transition-->
<!--                    duration-150-->
<!--                    ease-in-out-->
<!--                "-->
<!--               value="dave@unitiweb.com"-->
<!--        />-->
<!--    </div>-->
</template>

<script>

    export default {

        props: {
            label: {
                type: String,
                default: null
            },
            name: {
                type: String
            },
            type: {
                type: String,
                default: 'text'
            },
            value: {
                type: [Number, String]
            },
            placeholder: {
                type: String
            }
        },

        methods: {
            input (value) {
                this.$emit('input', value)
            }
        }

    }

</script>
