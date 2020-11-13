<template>

    <div v-show="show" class="md:hidden">
        <div class="fixed inset-0 flex z-40">
            <transition enter-active-class="transition-opacity ease-linear duration-300"
                        enter-class="opacity-0"
                        enter-to-class="opacity-100"
                        leave-active-class="transition-opacity ease-linear duration-300"
                        leave-class="opacity-100"
                        leave-to-class="opacity-0">
                <div v-show="show" class="fixed inset-0">
                    <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
                </div>
            </transition>
            <transition enter-active-class="transition ease-in-out duration-300 transform"
                        enter-class="-translate-x-full"
                        enter-to-class="translate-x-0"
                        leave-active-class="transition ease-in-out duration-300 transform"
                        leave-class="translate-x-0"
                        leave-to-class="-translate-x-full">
                <div v-show="show" class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-gray-800">
                    <div class="absolute top-0 right-0 -mr-14 p-1">
                        <button @click="toggleShow" class="flex items-center justify-center h-12 w-12 rounded-full focus:outline-none focus:bg-gray-600" aria-label="Close sidebar">
                            <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-shrink-0 flex items-center px-4">
                        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-logo-on-dark.svg" alt="Workflow">
                    </div>
                    <div class="mt-5 flex-1 h-0 overflow-y-auto">
                        <nav class="px-2 space-y-1">
                            <!-- Mobile Sidebar Lines -->
                            <div v-for="link in links">
                                <sidebar-link mobile :to="link.to">{{ link.label }}</sidebar-link>
                            </div>
                        </nav>
                    </div>
                </div>
            </transition>
            <div class="flex-shrink-0 w-14">
                <!-- Dummy element to force sidebar to shrink to fit close icon -->
            </div>
        </div>
    </div>

</template>

<script>
    import SidebarLink from '@/components/ui/sidebar/SidebarLink'

    export default {

        components: {
            SidebarLink
        },

        props: {
            links: {
                type: Array,
                default: []
            }
        },

        data () {
            return {
                show: false
            }
        },

        methods: {
            toggleShow () {
                this.show = !this.show
                this.$emit('toggle-show', this.show)
            }
        }
    }
</script>
