<template>
    <div class="flex flex-no-wrap">
        <input :type="type"
               class="border border-gray-500 rounded-l-md h-10 p-4 my-1"
               :class="inputClasses"
               :style="{ width: `${width}px` }"
               :value="value"
               @input="input"
               @keypress.enter="save"
        />
        <button class="border text-white bg-blue-400 border border-blue-500 h-10 px-2 my-1" @click="save">
            <icon name="save"></icon>
        </button>
        <button class="border text-white bg-red-400 border border-red-500 rounded-r-md h-10 px-2 my-1" @click="cancel">
            <icon name="x"></icon>
        </button>
    </div>
</template>

<script>
    export default {

        components: {},

        props: {
            type: {
                type: String,
                default: 'text'
            },
            value: {
                type: [String, Number, Boolean]
            },
            width: {
                type: Number,
                default: 150
            },
            right: {
                type: Boolean,
                default: false
            },
            placeholder: {
                type: String
            }
        },

        computed: {
            inputClasses () {
                const classes = []

                if (this.right) {
                    classes.push('text-right')
                }

                return classes
            }
        },

        methods: {
            input (e) {
                this.$emit('input', e.target.value)
            },
            update (cancel) {
                if (cancel) {
                    this.$emit('update', null)
                } else {
                    this.$emit('update', this.value)
                }
            },
            save () {
                this.update()
            },
            cancel () {
                this.update(true)
            }
        }

    }

</script>
