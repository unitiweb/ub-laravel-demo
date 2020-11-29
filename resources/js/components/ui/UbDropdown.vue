<template>

    <div>
        <a href="#" @click.prevent="show = !show" class=" px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium text-gray-900">
            View
        </a>
        <transition enter-active-class="transition ease-out duration-200"
                    enter-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95">
            <div v-show="show" class="absolute mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                <a v-for="(item, index) in items"
                   :key="`dropdown-${index}`"
                   href="#"
                   :class="{ 'font-bold': true }"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                   role="menuitem"
                >
                    <icon name="check" class="h-5 w-5"></icon>
                    {{ item.label }}
                </a>
            </div>
        </transition>
    </div>


</template>

<script>
    export default {

        props: {
            items: Array,
            default: function () {
                return []
            }
        },

        data () {
            return {
                show: false
            }
        }

    }
</script>
