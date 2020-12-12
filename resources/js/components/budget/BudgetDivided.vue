<template>
    <div class="grid grid-cols-5 gap-x-2">
        <div class="col-span-5 md:col-span-3 rounded-md rounded-r-none">
            <slot name="left">
                Right
            </slot>
        </div>
        <div class="col-span-5 md:col-span-2 bg-gray-100 border border-gray-400 rounded-md shadow-md my-1">
            <slot name="right"/>
        </div>
    </div>
</template>
