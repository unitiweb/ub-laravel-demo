<template>
    <div>
        <div v-if="account.enabled" :class="classes" class="flex border-b rounded-md rounded-b-none cursor-pointer m-1 pb-1">
            <div @click="modify" class="flex-1 px-2 pt-1">
                {{ account.name }}
                <span class="text-xs text-gray-500">{{ account.subType }}</span>
                <div class="text-xs text-gray-500">
                    {{ account.officialName }}
                </div>
            </div>
            <div @click="modify" class="flex-none">
                <div class="text-right text-lg amount-width px-3" :width="125">
                    {{ account.balances.available | currency }}
                    <div class="text-xs text-gray-500">
                        {{ account.balances.current | currency }}
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="!account.enabled && !collapsed" :class="classes" class="flex bg-gray-100 border-b rounded-md rounded-b-none m-1 pb-1">
            <div class="flex-1 px-2 pt-1">
                {{ account.name }}
                <span class="text-xs text-gray-500">{{ account.subType }}</span>
                <div class="text-xs text-gray-500">
                    {{ account.officialName }}
                </div>
            </div>
            <div class="flex-none pt-2">
                <div class="text-right text-lg amount-width px-3" :width="125">
                    <ub-button @click="showEnableDialog = true" size="xs" variant="secondary" outline>enable</ub-button>
                </div>
            </div>
        </div>
        <modal v-if="showEnableDialog"
               variant="warning"
               title="Enable Bank Account?"
               confirm-label="Yes, Enable"
               cancel-label="Oops! No"
               @confirm="accountEnableConfirm"
               @cancel="showEnableDialog = false">
            Do you really want to enable this bank account.
            Once enabled transactions will be loaded and updated periodically
        </modal>
    </div>
</template>

<script>
    import Modal from '@/components/ui/modal/Modal'
    import { mapGetters, mapActions } from 'vuex'

    export default {

        components: {
            Modal
        },

        props: {
            institution: {
                type: Object
            },
            account: {
                type: Object
            },
            collapsed: {
                type: Boolean
            }
        },

        data () {
            return {
                showEnableDialog: false,
            }
        },

        computed: {
            ...mapGetters(['loading']),

            classes () {
                const classes = []
                classes.push('bg-white hover:bg-gray-100')
                return classes
            }
        },

        methods: {
            ...mapActions(['setLoading']),

            async accountEnableConfirm () {
                await this.setLoading(true)
                try {
                    await this.$http.financialUpdateAccount(
                        this.institution.id,
                        this.account.id,
                        {enabled: true}
                    )
                } catch ({ error }) {
                    console.log('error', error)
                }
                await this.setLoading(false)
                this.account.enabled = true
                this.showEnableDialog = false
            },

            modify () {
                this.$emit('modify', this.account)
            }
        }
    }

</script>
