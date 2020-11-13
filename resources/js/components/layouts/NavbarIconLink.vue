<template>
    <button class="flex-shrink-0 border-2 border-transparent rounded-full p-1 text-light-blue-200 hover:bg-light-blue-800 hover:text-white focus:outline-none focus:text-white focus:bg-light-blue-800 transition duration-150 ease-in-out">
        <span class="sr-only">{{ label }}</span>
        <!-- Heroicon name: bell -->
        <icon :type="icon"></icon>
        <slot/>
    </button>
</template>

<script>
    import Icon from "@/components/ui/Icon";

    export default {

        components: {
            Icon
        },

        props: {
            label: {
                type: String
            },
            icon: {
                type: String,
                default: 'unknown'
            }
        }

    }
</script>
