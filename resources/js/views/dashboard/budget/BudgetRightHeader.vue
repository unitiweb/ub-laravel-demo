<template>
    <div class="flex justify-between h-10 p-1 m-1">
        <div>
            <slot name="left"/>
        </div>
        <div class="font-bold text-lg">
            <slot name="title">
                {{ title }}
            </slot>
        </div>
        <div>
            <slot name="right"/>
        </div>
    </div>
</template>

<script>

    export default {

        props: {
            title: {
                type: String
            }
        }
    }

</script>
