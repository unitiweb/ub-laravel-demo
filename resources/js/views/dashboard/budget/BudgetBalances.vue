<template>
    <div>
        <div v-if="balances" class="flex rounded-md rounded-t-none">
            <div class="flex-1 text-sm text-center bg-red-100 border border-gray-300 border-l-0 border-r border-t-0 border-b-0">
                <div class="text-sm display-inline">{{ balances.expenses | currency }}</div>
                <div class="text-xs display-inline">Expenses</div>
            </div>
            <div :class="outstandingVariant" class="flex-1 text-xs text-center border border-gray-300 border-l-0 border-r border-t-0 border-b-0">
                <div class="text-sm">{{ balances.outstanding | currency }}</div>
                <div class="text-xs">Out Standing</div>
            </div>
            <div :class="leftOverVariant" class="flex-1 text-xs text-center">
                <div class="text-sm">{{ balances.leftOver | currency }}</div>
                <div class="text-xs">Left Over</div>
            </div>
        </div>
        <div v-else class="text-center text-xs bg-red-50 rounded-md rounded-t-none p-1">
            no balances available
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            balances: {
                type: Object
            }
        },

        computed: {
            outstandingVariant () {
                if (this.balances.outstanding === 0) {
                    return 'bg-green-100'
                } else if (this.balances.outstanding === this.balances.expenses) {
                    return 'bg-gray-100'
                }

                return 'bg-yellow-100'
            },

            leftOverVariant () {
                if (this.balances.leftOver === 0) {
                    return 'bg-yellow-100'
                } else if (this.balances.leftOver < 0) {
                    return 'bg-red-100'
                }

                return 'bg-green-100'
            },
        }
    }

</script>
