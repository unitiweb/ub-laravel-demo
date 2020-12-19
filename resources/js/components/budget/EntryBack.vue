<template>
    <div class="flex border border-gray-400 bg-gray-100 rounded-md shadow mx-1 my-2">
        <div class="flex-none border-r border-gray-400 hover:bg-blue-200 py-1">
            <icon size="6" name="order-bar" class="cursor-move text-gray-700"></icon>
            <icon name="link" size="3" class="cursor-pointer float-left ml-1 mt-1"></icon>
        </div>
        <div class="flex-1 cursor-pointer hover:bg-gray-200 px-3 py-1" style="width: 50%;">
            {{ entry.name }}
            <div class="text-xs text-gray-600">
                <span class="float-left">Due: <due-day :value="entry.dueDay"></due-day></span>

            </div>
        </div>
        <div class="flex-none text-lg text-right cursor-pointer px-3 py-3 py-1 hover:bg-blue-200 hover:border-2 hover:border-blue-700" style="width: 125px;">
            <currency :value="entry.amount"/>
        </div>
        <div class="flex-none text-lg text-right cursor-pointer px-3 py-4 hover:bg-blue-200 hover:border-2 hover:border-blue-700" style="width: 125px;">

            <div class="flex">
                <label class="flex items-center">
                    <input type="checkbox" class="bg-red-500 h-4 w-4"></input>
<!--                    <t-checkbox name="entryStatus" variant="error" v-model="status.goal" :value="true" :uncheckedValue="false"-->
<!--                        class="form-checkbox transition duration-150 ease-in-out text-red-500 shadow-md shadow-red-500"/>-->
<!--                    <span class="ml-2 text-sm hidden sm:visible">Goal</span>-->
                </label>
            </div>

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
            entry: {
                type: Object
            }
        },

        data () {
            return {
                status: {
                    goal: true,
                    paid: false,
                    cleared: false
                }
            }
        }

    }
</script>
