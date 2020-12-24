<template>
    <div class="space-y-1">
        <label class="block text-sm pl-2 leading-5 font-medium text-gray-700">
            {{ label }}
        </label>
        <div class="relative">
            <span class="inline-block w-full z-30 rounded-md shadow-sm">
                <button @click="toggle" @blur="blur" type="button" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label" class="cursor-default relative w-full rounded-md border border-gray-300 bg-white pl-3 pr-10 py-2 text-left focus:outline-none focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    <span class="block text-gray-700 truncate">
                        <slot name="selected-label">
                            {{ selectedValue }}
                        </slot>
                    </span>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <icon name="selector" class="text-gray-700"></icon>
                    </span>
                </button>
            </span>
            <transition enter-active-class=""
                        enter-class=""
                        enter-to-class=""
                        leave-active-class="transition ease-in duration-100"
                        leave-class="opacity-100"
                        leave-to-class="opacity-0">
                <div v-show="opened" class="absolute mt-1 w-full z-50 rounded-md bg-white border border-gray-300 shadow-lg">
                    <ul tabindex="-1" role="listbox" class="max-h-60 rounded-md py-1 text-base leading-6 shadow-xs overflow-auto focus:outline-none sm:text-sm sm:leading-5">
                        <ub-select-option v-for="(option, index) in options" :key="`select-option-${index}`" :selected="selected(option)" @input="select(option)" :value="option[idKey]" :label="option[labelKey]"/>
                        <slot/>
                    </ul>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>

export default {

    components: {},

    props: {
        label: {
            type: String,
            default: ''
        },
        value: {
            type: [String, Number],
            default: null
        },
        idKey: {
            description: 'The option id key to be used to determine which option is selected',
            type: String,
            default: 'id'
        },
        labelKey: {
            description: 'The option label key to be used to determine which option is selected',
            type: String,
            default: 'label'
        },
        options: {
            type: Array,
            default: () => {
                return []
            }
        },
        placeholder: {
            type: String,
            default: ''
        }
    },

    data () {
        return {
            opened: false
        }
    },

    computed: {
        selectOptions () {
            return this.options.map(option => {
                return {
                    id: option[this.idKey],
                    label: option[this.labelKey]
                }
            })
        },
        selectedValue () {
            const selected = this.options.find(item => item[this.idKey] === this.value)
            if (selected) {
                return selected[this.labelKey]
            }

            return this.placeholder
        }
    },

    methods: {
        selected (option) {
            return option[this.idKey] === this.value
        },
        toggle () {
            this.opened = !this.opened
        },
        select (value) {
            console.log(value)
            this.$emit('input', value[this.idKey])
            this.$emit('updated', value[this.idKey], value[this.labelKey])
        },
        blur () {
            setTimeout(() => {
                this.opened = false
            }, 100)
        }
    }

}

</script>
