<template>
    <li role="option" class="border border-b-0 border-t border-x-0 text-gray-700 select-none relative px-4">
        <span v-if="$slots.default" class="font-bold block truncate pt-2">
            <slot/>
        </span>
    </li>
</template>

<script>

    export default {
        props: {
            label: String,
            default: null
        }
    }

</script>
