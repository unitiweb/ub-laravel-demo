<template>
    <div>
        <institution v-for="institution in bankInstitutions"
                     :key="institution.institutionId"
                     :institution="institution"
                     :active="active"
                     @modify-account="modifyAccount">
        </institution>
    </div>
</template>

<script>
    import Institution from '@/views/dashboard/banks/Institution'
    import { mapGetters } from 'vuex'

    export default {

        components: {
            Institution
        },

        props: {
            active: {
                type: [String, Number]
            }
        },

        computed: {
            ...mapGetters(['bankInstitutions'])
        },

        methods: {
            modifyAccount (account) {
                this.$emit('modify-account', account)
            }
        }

    }

</script>
