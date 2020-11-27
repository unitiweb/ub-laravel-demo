<template>
    <div class="space-y-1">
        <label class="block text-sm pl-2 leading-5 font-medium text-gray-700">
            {{ label }}
        </label>
        <div class="relative">
            <span class="inline-block w-full z-30 rounded-md shadow-sm">
                <button @click="toggle" @blur="blur" type="button" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label" class="cursor-default relative w-full rounded-md border border-gray-400 bg-white pl-3 pr-10 py-2 text-left focus:outline-none focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    <span class="block text-gray-700 truncate">
                        {{ selectedValue }}
                    </span>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <icon name="selector" class="h-5 w-5 text-gray-700"></icon>
                    </span>
                </button>
            </span>
            <transition enter-active-class=""
                        enter-class=""
                        enter-to-class=""
                        leave-active-class="transition ease-in duration-100"
                        leave-class="opacity-100"
                        leave-to-class="opacity-0">
                <div v-show="opened" class="absolute mt-1 w-full z-40 rounded-md bg-white border border-gray-400 shadow-lg">
                    <ul tabindex="-1" role="listbox" class="max-h-60 rounded-md py-1 text-base leading-6 shadow-xs overflow-auto focus:outline-none sm:text-sm sm:leading-5">
                        <li v-for="option in options" @click="select(option)" role="option" class="text-gray-700 cursor-pointer select-none relative hover:bg-gray-100 hover:border-gray-200 py-2 pl-8 pr-4">
                            <span :class="{ 'font-semibold': selected(option), 'font-normal': !selected(option) }" class="font-normal block truncate">
                                {{ option.label }}
                            </span>
                                <span :class="{ 'text-white': !selected(option), 'text-indigo-600': selected(option) }" class="absolute inset-y-0 left-0 flex items-center pl-2">
                                <icon name="check" class="h-5 w-5"></icon>
                            </span>
                        </li>
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
            type: String
        },
        value: {
            type: [String, Number]
        },
        allowNone: {
            type: Boolean,
            default: false
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
        selectedValue () {
            const selected = this.options.find(item => item.id === this.value)
            if (selected) {
                return selected.label
            }

            return this.placeholder
        }
    },

    methods: {
        selected (option) {
            return option.id === this.value
        },
        toggle () {
            this.opened = !this.opened
        },
        select (option) {
            this.$emit('input', option.id)
            this.$emit('updated', option)
            // setTimeout(() => {
            //     this.opened = false
            // }, 300)
        },
        blur () {
            setTimeout(() => {
                this.opened = false
            }, 100)
        }
    }

}

</script>
