<template>
    <transition enter-active-class=""
                enter-class=""
                enter-to-class=""
                leave-active-class="transition ease-in duration-100"
                leave-class="opacity-100"
                leave-to-class="opacity-0">
        <slot/>
    </transition>
</template>

<script>

export default {}

</script>
