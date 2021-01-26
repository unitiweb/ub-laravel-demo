<template>
    <page-header>
        <template v-slot:left>
            <icon name="officeBuilding" size="6" class="text-blue-500 mr-1"></icon>
            Banks
        </template>
        <template v-slot:right>
            <add-bank-button @refresh="refresh" @success="success">
                Add Bank
            </add-bank-button>
            <ub-button @click="refresh" variant="secondary" outline size="sm" icon="refresh"/>
        </template>

    </page-header>
</template>

<script>
    import PageHeader from '@/components/layouts/PageHeader'
    import AddBankButton from '@/views/dashboard/banks/AddBankButton'

    export default {

        components: {
            PageHeader,
            AddBankButton
        },

        methods: {
            refresh () {
                this.$emit('refresh')
            },

            success () {
                this.$emit('institution-added')
            }
        }
    }

</script>
