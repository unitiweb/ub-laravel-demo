<template>
    <label class="flex items-center">
        <t-checkbox name="entryStatus" v-model="value" :value="true" :uncheckedValue="false" @change="input" />
        <span class="ml-2 text-sm">{{ label }}</span>
    </label>
</template>

<script>

export default {

    props: {
        value: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {

        }
    },

    methods: {
        input (value) {
            console.log('checkbox value', value)
            this.$emit('input', value)
        }
    }

}

</script>
