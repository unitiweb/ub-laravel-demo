<template>
    <transition enter-active-class="transition ease-out duration-100"
                enter-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
        <div class="bg-light-blue-900 lg:hidden">
            <div class="pt-2 pb-3 px-2 space-y-1">
                <navbar-menu-link @click="close" :to="{ name: 'budget' }">Budget</navbar-menu-link>
                <navbar-menu-link @click="close" :to="{ name: 'banks' }">Banks</navbar-menu-link>
            </div>
            <div class="pt-4 pb-3 border-t border-light-blue-800">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <avatar :size="10" rounded/>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium leading-6 text-white">{{ fullName }}</div>
                        <div class="text-sm font-medium leading-5 text-light-blue-200">{{ email }}</div>
                    </div>
                    <button class="ml-auto flex-shrink-0 border border-transparent rounded-full p-1 text-light-blue-200 hover:bg-light-blue-800 hover:text-white focus:outline-none focus:text-white focus:bg-light-blue-700 transition duration-150 ease-in-out">
                        <span class="sr-only">Notifications</span>
                        <navbar-icon-link icon="bell"/>
                    </button>
                </div>
                <div class="mt-3 px-2">
                    <navbar-menu-link @click="close" :to="{ name: 'profile' }" class="text-light-blue-200 font-weight-light">My Profile</navbar-menu-link>
                    <navbar-menu-link @click="close" :to="{ name: 'lock' }" class="text-light-blue-200">Lock Login</navbar-menu-link>
                    <navbar-menu-link @click="close" :to="{ name: 'logout' }" class="text-light-blue-200">Logout</navbar-menu-link>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import NavbarMenuLink from '@/components/layouts/NavbarMenuLink'
    import NavbarIconLink from '@/components/layouts/NavbarIconLink'
    import Avatar from '@/components/ui/Avatar'
    import { mapGetters } from 'vuex'

    export default {

        components: {
            NavbarMenuLink,
            NavbarIconLink,
            Avatar
        },

        computed: {
            ...mapGetters(['fullName', 'email']),
        },

        methods: {
            close () {
                console.log('close')
                this.$emit('close')
            }
        }

    }

</script>
