<template>
    <router-link :to="to"
                 :class="classes"
                 :active-class="activeClasses">
        <!-- Heroicon name: home -->
        <svg class="mr-4 h-6 w-6 text-gray-300 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <slot/>
    </router-link>
</template>

        group flex items-center px-2 py-2 font-medium rounded-md text-white bg-gray-900 focus:outline-none focus:bg-gray-700 transition ease-in-out duration-150
mobile: group flex items-center px-2 py-2 font-medium rounded-md text-white bg-gray-900 focus:outline-none focus:bg-gray-700 transition ease-in-out duration-150

active:        text-sm leading-5
active mobile: leading-6 text-base

<script>
export default {

    props: {
        label: {
            type: String
        },
        to: {
            type: [String, Object]
        },
        mobile: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        classes () {
            return [
                'group',
                'flex',
                'items-center',
                'px-2',
                'py-2',
                'font-medium',
                'rounded-md',
                'text-white',
                'focus:outline-none',
                'focus:bg-gray-700',
                'transition',
                'ease-in-out',
                'duration-150'
            ]
        },
        activeClasses () {
            if (this.mobile) {
                return [
                    'bg-gray-900',
                    'leading-6',
                    'text-base'
                ].join(' ')
            }

            return [
                'bg-gray-900',
                'text-sm',
                'leading-5'
            ].join(' ')
        }
    }
}
</script>
