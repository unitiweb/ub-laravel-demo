<template>
    <card class="max-w-3xl">
        <template v-slot:header>
            <h2 class="text-lg leading-6 font-medium text-gray-900">Personal Information</h2>
            <p class="mt-1 text-sm leading-5 text-gray-500">Use a permanent address where you can recieve mail.</p>
        </template>

        <div class="col-span-6 sm:col-span-3">
            <f-input label="First Name"
                     description="This is a description of the input field"
                     hint="Options"
                     :success="true"
                     :error="false"
                     feedback="Everything is A-OK"
                     left-add-on="http://"
            ></f-input>
<!--            <label for="first_name" class="block text-sm font-medium leading-5 text-gray-700">First-->
<!--                name</label>-->
<!--            <input id="first_name" class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">-->
        </div>

<!--        <div class="col-span-6 sm:col-span-3">-->
<!--            <label for="last_name" class="block text-sm font-medium leading-5 text-gray-700">Last name</label>-->
<!--            <input id="last_name" class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">-->
<!--        </div>-->

<!--        <div class="col-span-6 sm:col-span-4">-->
<!--            <label for="email_address" class="block text-sm font-medium leading-5 text-gray-700">Email address</label>-->
<!--            <input id="email_address" class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">-->
<!--        </div>-->

<!--        <div class="col-span-6 sm:col-span-3">-->
<!--            <label for="country" class="block text-sm font-medium leading-5 text-gray-700">Country / Region</label>-->
<!--            <select id="country" class="form-select mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">-->
<!--                <option>United States</option>-->
<!--                <option>Canada</option>-->
<!--                <option>Mexico</option>-->
<!--            </select>-->
<!--        </div>-->

<!--        <div class="col-span-6">-->
<!--            <label for="street_address" class="block text-sm font-medium leading-5 text-gray-700">Street address</label>-->
<!--            <input id="street_address" class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">-->
<!--        </div>-->

<!--        <div class="col-span-6 sm:col-span-6 lg:col-span-2">-->
<!--            <label for="city" class="block text-sm font-medium leading-5 text-gray-700">City</label>-->
<!--            <input id="city" class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">-->
<!--        </div>-->

<!--        <div class="col-span-6 sm:col-span-3 lg:col-span-2">-->
<!--            <label for="state" class="block text-sm font-medium leading-5 text-gray-700">State / Province</label>-->
<!--            <input id="state" class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">-->
<!--        </div>-->

<!--        <div class="col-span-6 sm:col-span-3 lg:col-span-2">-->
<!--            <label for="postal_code" class="block text-sm font-medium leading-5 text-gray-700">ZIP / Postal</label>-->
<!--            <input id="postal_code" class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">-->
<!--        </div>-->

        <template v-slot:footer>
            <div class="text-right">
                <f-button>Save</f-button>
            </div>
        </template>
    </card>

</template>

<script>

export default {

    components: {},

    props: {},

    computed: {},

    methods: {}

}

</script>
