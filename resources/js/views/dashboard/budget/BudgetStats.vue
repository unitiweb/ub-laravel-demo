<template>
    <div>
        <budget-right-header title="Budget Status">
        </budget-right-header>
        <div class="bg-gray-100 border border-gray-300 rounded-md shadow-md p-4 grid grid-cols-2 gap-6">
            <div>
                Stats will go here
            </div>
        </div>
    </div>
</template>

<script>

    import BudgetRightHeader from '@/views/dashboard/budget/BudgetRightHeader'

    export default {
        components: {
            BudgetRightHeader
        }
    }

</script>
