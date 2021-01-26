<template>
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
        <div class="relative h-16 flex items-center justify-between lg:border-b lg:border-light-blue-800">
            <div class="px-2 flex items-center lg:px-0">
                <div class="flex-shrink-0">
                    <logo size="sm"></logo>
                </div>
                <div class="hidden lg:block lg:ml-6 lg:space-x-4">
                    <div class="flex">
                        <navbar-link :to="{ name: 'budget' }">Budget</navbar-link>
                        <navbar-link :to="{ name: 'banks' }">Banks</navbar-link>
                        <navbar-link :to="{ name: 'profile' }">My Profile</navbar-link>
                        <navbar-link :to="{ name: 'lock' }">Lock</navbar-link>
                        <navbar-link :to="{ name: 'logout' }">Logout</navbar-link>
                    </div>
                </div>
            </div>
            <div class="flex-1 px-2 flex justify-center lg:ml-6 lg:justify-end">
<!--                <navbar-search id="search" placeholder="Search" type="search"/>-->
            </div>
            <div class="flex lg:hidden">
                <!-- Mobile menu button -->
                <navbar-button @toggle="toggle"></navbar-button>
            </div>
            <div class="hidden lg:block lg:ml-4">
                <div class="flex items-center">
                    <navbar-icon-link icon="bell"/>
                    <navbar-user></navbar-user>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Logo from '@/components/ui/logo/Logo'
    import NavbarLink from "@/components/layouts/NavbarLink";
    import NavbarIconLink from "@/components/layouts/NavbarIconLink";
    import NavbarUser from "@/components/layouts/NavbarUser";
    import NavbarButton from "@/components/layouts/NavbarButton";
    import NavbarSearch from "@/components/layouts/NavbarSearch";

    export default {

        components: {
            NavbarLink,
            NavbarIconLink,
            NavbarUser,
            NavbarButton,
            NavbarSearch,
            Logo
        },

        methods: {
            toggle () {
                this.$emit('toggle')
            }
        }
    }

</script>
