<template>
    <nav class="bg-white rounded-lg rounded-b-none shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center text-2xl font-bold text-gray-700 sm:text-2xl sm:truncate">
                        <slot name="left"/>
                    </div>
                </div>
                <div class="flex gap-x-2 items-center">
                    <slot name="right"/>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>

    export default {}

</script>
