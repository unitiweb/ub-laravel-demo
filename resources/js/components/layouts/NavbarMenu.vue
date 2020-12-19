<template>
    <transition enter-active-class="transition ease-out duration-100"
                enter-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
        <div class="bg-light-blue-900 lg:hidden">
            <div class="pt-2 pb-3 px-2 space-y-1">
                <navbar-menu-link :to="{ name: 'budget' }">Dashboard</navbar-menu-link>
                <navbar-menu-link :to="{ name: 'lock' }">Lock</navbar-menu-link>
                <navbar-menu-link :to="{ name: 'logout' }">Logout</navbar-menu-link>
            </div>
            <div class="pt-4 pb-3 border-t border-light-blue-800">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <img class="rounded-full h-10 w-10" src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=4&w=256&h=256&q=80h" alt="">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium leading-6 text-white">Debbie Lewis</div>
                        <div class="text-sm font-medium leading-5 text-light-blue-200">debbielewis@example.com</div>
                    </div>
                    <button class="ml-auto flex-shrink-0 border-2 border-transparent rounded-full p-1 text-light-blue-200 hover:bg-light-blue-800 hover:text-white focus:outline-none focus:text-white focus:bg-light-blue-700 transition duration-150 ease-in-out">
                        <span class="sr-only">Notifications</span>
                        <navbar-icon-link icon="bell"/>
                    </button>
                </div>
                <div class="mt-3 px-2">
                    <navbar-menu-link :to="{ name: 'login' }" class="text-light-blue-200 font-weight-light">Your Profile</navbar-menu-link>
                    <navbar-menu-link :to="{ name: 'login' }" class="text-light-blue-200">Settings</navbar-menu-link>
                    <navbar-menu-link :to="{ name: 'logout' }" class="text-light-blue-200">Sign Out</navbar-menu-link>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import NavbarMenuLink from "@/components/layouts/NavbarMenuLink";
    import NavbarIconLink from "@/components/layouts/NavbarIconLink";

    export default {

        components: {
            NavbarMenuLink,
            NavbarIconLink
        },

        props: {
            // show: {
            //     type: Boolean,
            //     default: false
            // }
        },



    }

</script>
