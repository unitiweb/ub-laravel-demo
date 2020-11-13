<template>
    <div class="relative flex-shrink-0 ml-4">
        <div>
            <button @click="click"
                    @blur="blur"
                    class="rounded-full flex text-sm text-white focus:outline-none focus:shadow-solid transition duration-150 ease-in-out"
                    id="user-menu"
                    aria-haspopup="true">
                <span class="sr-only">Open profile menu</span>
                <img class="rounded-full h-8 w-8" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=80" alt="">
            </button>
        </div>
        <transition enter-active-class="transition ease-out duration-100"
                    enter-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95">
            <div v-show="show" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow">
                <div class="py-1 bg-white rounded-md shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                    <router-link :to="{ name: 'dashboard' }"
                                 class="block py-2 px-4 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 hover:bg-gray-200 transition duration-150 ease-in-out"
                                 role="menuitem">
                        Your Profile
                    </router-link>
                    <router-link :to="{ name: 'dashboard' }"
                                 class="block py-2 px-4 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 hover:bg-gray-200 transition duration-150 ease-in-out"
                                 role="menuitem">
                        Settings
                    </router-link>
                    <router-link :to="{ name: 'lock' }"
                                 class="block py-2 px-4 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 hover:bg-gray-200 transition duration-150 ease-in-out"
                                 role="menuitem">
                        Lock
                    </router-link>
                    <router-link :to="{ name: 'logout' }"
                                 class="block py-2 px-4 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 hover:bg-gray-200 transition duration-150 ease-in-out"
                                 role="menuitem">
                        Sign out
                    </router-link>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>

    export default {

        data () {
            return {
                show: false
            }
        },

        methods: {
            click () {
                this.show = !this.show
                this.$emit('click')
            },
            blur () {
                // This will allow the links to be clicked before hiding the menu
                setTimeout(() => {
                    this.show = false
                }, 100)
            }
        }

    }

</script>
