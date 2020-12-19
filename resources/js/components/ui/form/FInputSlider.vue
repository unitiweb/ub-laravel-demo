<template>
    <div>
        <input class="rounded-lg overflow-hidden appearance-none bg-gray-400 h-3 w-128"
               type="range"
               min="1"
               max="100"
               step="1"
               :value="value"
               @input="input"/>
    </div>
</template>

<script>

    export default {

        props: {
            value: {
                type: Number,
                default: 15
            }
        },


        methods: {
            input (value) {
                this.$emit('input', value)
            }
        }

    }

</script>
