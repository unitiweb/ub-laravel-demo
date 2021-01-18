<template>
    <div class="sticky top-0">
        <div :class="classes" class="hidden md:flex border border-t-0 border-l-0 border-r-0 border-b border-gray-300 rounded-md rounded-b-none">
            <!-- Medium Income Header-->
            <div class="flex-none text-lg text-right px-3 py-3">
                <ub-button @click="setCollapse"
                           size="sm" variant="secondary"
                           :icon="collapsed ? 'chevronDoubleRight' : 'chevronDoubleDown'"
                           outline>
                </ub-button>
            </div>
            <!-- Income Title -->
            <div class="flex-1 text-xl pb-2 pt-1">
                {{ institution.name }}
                <div class="text-xs text-gray-600"><a :href="institution.url" target="_blank">{{ institution.url }}</a></div>
            </div>
            <!-- Create Entry Button -->
            <div class="flex-none text-lg text-right px-3 py-2">
                <img class="w-10 h-10 bg-gray-300 rounded-full flex-shrink-0" :src="logo" :alt="institution.name"/>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            institution: {
                type: Object
            },
            collapsed: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            classes () {
                return ' bg-gray-100 hover:bg-gray-200'
            },

            logo () {
                return 'data:image/png;base64, ' + this.institution.logo
            }
        },

        methods: {
            setCollapse () {
                this.$emit('toggle-collapsed')
            }
        },

        mounted () {
            console.log('institution', this.institution)
        }

    }

</script>
