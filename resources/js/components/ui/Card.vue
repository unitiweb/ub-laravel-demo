<template>
    <div class="rounded-lg shadow-lg m-2 border-2 border-gray-400 mx-auto">
        <div class="p-4 border-b rounded-t-lg">
            <slot name="header">
                {{ header }}
            </slot>
        </div>
        <div class="bg-white rounded-md text-gray-700 p-4">
            <slot/>
        </div>
        <div class="p-4 border-t rounded-b-lg">
            <slot name="footer">
                {{ footer }}
            </slot>
        </div>
    </div>


<!--    <t-card :variant="variant" :header="title">-->

<!--        <template v-slot:header>-->
<!--            <slot name="header"/>-->
<!--        </template>-->

<!--        <slot/>-->

<!--        <template v-slot:footer>-->
<!--            <slot name="footer"/>-->
<!--        </template>-->
<!--    </t-card>-->
</template>

<script>
    export default {

        props: {
            header: {
                type: String
            },
            footer: {
                type: String
            },
            variant: {
                type: String,
                default: 'secondary',
                validator: value => {
                    return ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'outline'].indexOf(value) !== -1
                }
            }
        }
    }
</script>
