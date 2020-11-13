<template>
    <t-card :variant="variant" :header="title">

        <template v-slot:header>
            <slot name="header"/>
        </template>

        <slot/>

        <template v-slot:footer>
            <slot name="footer"/>
        </template>
    </t-card>
</template>

<script>
    export default {

        props: {
            title: {
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
