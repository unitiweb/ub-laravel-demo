<template>
    <div class="h-screen h-auto rounded-md rounded-r-none flex overflow-hidden">
        <div class="bg-gray-100 border border-gray-400 rounded-md rounded-r-none shadow-md flex flex-shrink-0">
            <div class="flex flex-col" :class="collapsed ? 'w-16' : 'w-64'">
                <div class="flex flex-col flex-grow pb-4 overflow-y-auto">
                    <div class="mt-2 flex-1 flex flex-col">
                        <nav class="flex-1 px-2 space-y-1">

                            <a href="#" @click.prevent="toggleCollapse" class="group flex items-center px-2 py-2 text-sm text-gray-700 font-medium text-white">
                                <icon name="menu" class="h-5 w-5" :class="{'mr-3': !collapsed, 'ml-1': collapsed}"></icon>
                            </a>

                            <a href="#" class="group flex items-center px-2 py-2 text-sm text-gray-700 font-medium text-white">
                                <!-- Heroicon name: home -->
                                <svg :class="{'mr-3': !collapsed, 'ml-1': collapsed}" class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span v-show="!collapsed">
                                    Dashboard
                                </span>
                            </a>

                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <main class="flex-1 relative overflow-y-auto focus:outline-none" tabindex="0">
                <div class="py-0 pl-2">
                    <slot/>
                </div>
            </main>
        </div>
    </div>
</template>

<script>

    export default {

        data () {
            return {
                collapsed: false
            }
        },

        methods: {
            followLink (link) {

            },

            toggleCollapse () {
                this.collapsed = !this.collapsed
            }
        }

    }

</script>
