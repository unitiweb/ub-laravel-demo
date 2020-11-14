<template>
    <div class="border border-gray-400 rounded-md shadow-md mx-1 mt-2 mb-3">
        <div class="flex bg-gray-200 border border-t-0 border-l-0 border-r-0 border-b border-gray-400 rounded-t-md">
            <div class="flex-1 text-lg cursor-pointer hover:bg-gray-200 px-3 py-2">
                {{ income.name }}
                <span class="text-xs text-gray-600">Due: <due-day :value="income.dueDay"></due-day></span>
            </div>
            <div class="flex-none text-lg text-right cursor-pointer px-3 py-2 hover:bg-blue-200 hover:border-2 hover:border-blue-700" style="width: 125px;">
                <currency :value="income.amount"/>
            </div>
        </div>
        <div>
            <slot/>
        </div>
    </div>
</template>

<script>
    import Currency from '@/components/ui/Currency'
    import DueDay from '@/components/ui/DueDay'

    export default {

        components: {
            Currency,
            DueDay
        },

        props: {
            income: {
                type: Object
            }
        },

        computed: {},

        methods: {}

    }

</script>
