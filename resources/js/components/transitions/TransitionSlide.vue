<template>
    <transition enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
                enter-class="translate-x-full"
                enter-to-class="translate-x-0"
                leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
                leave-class="translate-x-0"
                leave-to-class="translate-x-full">
        <slot/>
    </transition>
</template>
