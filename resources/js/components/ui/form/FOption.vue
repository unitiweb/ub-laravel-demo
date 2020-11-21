<template>
    <li @click="click" role="option" class="text-gray-700 cursor-pointer select-none relative hover:bg-gray-100 hover:border-gray-200 py-2 pl-8 pr-4">
        <span :class="{ 'font-semibold': selected, 'font-normal': !selected }" class="font-normal block truncate">
            <slot/>
        </span>
        <span :class="{ 'text-white': !selected, 'text-indigo-600': selected }" class="absolute inset-y-0 left-0 flex items-center pl-2">
            <icon name="check" class="h-5 w-5"></icon>
        </span>
    </li>
</template>

<script>

export default {

    components: {},

    props: {
        value: {
            type: [String, Number]
        },
        label: {
            type: String,
            default: 'none'
        },
        selected: {
            type: Boolean,
            default: false
        }
    },

    data() {
    },

    computed: {},

    methods: {
        click () {
            this.$emit('click', this.value)
        }
    }

}

</script>
