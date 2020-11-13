<template>
    <div>
        <div class="relative bg-light-blue-900 pb-32 overflow-hidden">
            <nav class="relative z-10 border-b border-teal-500 border-opacity-25 lg:bg-transparent lg:border-none">
                <navbar @toggle="toggleMobileMenu"></navbar>
                <navbar-menu v-show="showMobileMenu"></navbar-menu>
            </nav>
            <!-- On: "bottom-0", Off: "inset-y-0" -->
            <div class="absolute flex justify-center inset-x-0 left-1/2 transform -translate-x-1/2 w-full overflow-hidden lg:inset-y-0" aria-hidden="true">
                <div class="flex-grow bg-light-blue-900 bg-opacity-75"></div>
                <svg class="flex-shrink-0" width="1750" height="308" viewBox="0 0 1750 308" xmlns="http://www.w3.org/2000/svg">
                    <path opacity=".75" d="M1465.84 308L16.816 0H1750v308h-284.16z" fill="#075985" />
                    <path opacity=".75" d="M1733.19 0L284.161 308H0V0h1733.19z" fill="#0c4a6e" />
                </svg>
                <div class="flex-grow bg-light-blue-800 bg-opacity-75"></div>
            </div>
            <header class="relative p-4">
                <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl leading-9 font-bold text-white">
                        <slot name="header"></slot>
                    </h1>
                </div>
            </header>
        </div>
        <main v-show="!showMobileMenu" class="relative -mt-32">
            <div class="max-w-screen-xl mx-auto pb-6 px-1 lg:pb-16 lg:px-8">
                <div class="p-4 bg-white rounded-lg shadow-lg overflow-hidden">
                    <slot/>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
    import Navbar from'@/components/layouts/Navbar'
    import NavbarMenu from "@/components/layouts/NavbarMenu";

    export default {

        components: {
            Navbar,
            NavbarMenu
        },

        props: {
            pageTitle: {
                type: String
            }
        },

        data () {
            return {
                showMobileMenu: false
            }
        },

        computed: {
            page () {
                return this.$store.getters.page
            }
        },

        methods: {
            toggleMobileMenu () {
                this.showMobileMenu = !this.showMobileMenu
                console.log('showMobileMenu', this.showMobileMenu)
            }
        }

    }
</script>
