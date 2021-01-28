<template>
    <div class="border border-gray-300 rounded-md shadow-md m-1">
        <div>
            <institution-header :institution="institution" :collapsed="collapsed" @toggle-collapsed="toggleCollapsed"></institution-header>
            <institution-account v-for="account in institution.accounts"
                                 :key="`act-${account.id}`"
                                 :institution="institution"
                                 :account="account"
                                 :collapsed="collapsed"
                                 @modify="modify">
            </institution-account>
        </div>
    </div>
</template>

<script>
    import InstitutionHeader from '@/views/dashboard/banks/InstitutionHeader'
    import InstitutionAccount from '@/views/dashboard/banks/InstitutionAccount'

    export default {
        components: {
            InstitutionHeader,
            InstitutionAccount
        },

        props: {
            institution: {
                type: Object
            },
            active: {
                type: [String, Number]
            }
        },

        data () {
            return {
                collapsed: true
            }
        },

        computed: {
            logo () {
                return 'data:image/png;base64, ' + this.institution.logo
            }
        },

        methods: {
            toggleCollapsed () {
                this.collapsed = !this.collapsed
            },

            modify (account) {
                this.$emit('modify-account', account)
            },

            initialCollapse() {
                this.collapsed = false
                for (let i = 0; i < this.institution.accounts.length; i++) {
                    const account = this.institution.accounts[i]
                    if (account.enabled === true) {
                        this.collapsed = true
                        break;
                    }
                }
            }
        },

        mounted () {
            this.initialCollapse()
        }
    }

</script>
