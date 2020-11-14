<template>
<!--    <div class="flex">-->
        <label class="flex items-center">
            <t-checkbox :name="name" :value="value" @change="input" />
            <span v-if="label && showLabel" class="ml-2 text-sm">{{ label }}</span>
        </label>
<!--        <label class="flex items-center ml-2">-->
<!--            <t-checkbox name="options" value="b" />-->
<!--            <span class="ml-2 text-sm">Option B</span>-->
<!--        </label>-->
<!--    </div>-->
</template>

<script>

    export default {

        props: {
            name: {
                type: String,
                default: null
            },
            value: {
                type: [String, Object, Number, Boolean, Array],
                default: null
            },
            showLabel: {
                type: Boolean,
                default: true
            },
            label: {
                type: String,
                default: null
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
