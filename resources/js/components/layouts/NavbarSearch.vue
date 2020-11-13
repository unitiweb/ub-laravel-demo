<template>
    <div class="max-w-lg w-full lg:max-w-xs">
        <label for="search" class="sr-only">Search</label>
        <div class="relative text-light-blue-100 focus-within:text-gray-400">
            <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                <icon name="search"></icon>
<!--                <svg class="flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">-->
<!--                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />-->
<!--                </svg>-->
            </div>
            <input class="block w-full bg-light-blue-700 bg-opacity-50 py-2 pl-10 pr-3 border border-transparent rounded-md leading-5 placeholder-light-blue-100 focus:outline-none focus:bg-white focus:placeholder-gray-500 focus:text-gray-900 sm:text-sm transition duration-150 ease-in-out"
                   id="search"
                   :value="value"
                   @input="input"
                   placeholder="Search"
                   type="search"/>
        </div>
    </div>
</template>

<script>
    import Icon from "@/components/ui/Icon";

    export default {

        components: {
            Icon
        },

        props: {
            id: {
                type: String
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
