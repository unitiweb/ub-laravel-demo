<template>
    <div>
<!--        <ub-button @click="update" size="sm" :icon="icon" :variant="variant"/>-->
        <ub-button v-if="value.cleared" @click="update" size="sm" icon="check" variant="success"/>
        <ub-button v-else-if="value.paid" @click="update" size="sm" icon="dotsHorizontal" variant="warning"/>
        <ub-button v-else-if="value.goal" @click="update" size="sm" icon="dotsHorizontal" variant="danger"/>
        <ub-button v-else @click="update" size="sm" icon="dotsHorizontal" variant="secondary" outline/>
<!--        <div class="text-xs text-center">{{ label }}</div>-->
    </div>
</template>

<script>
    import { mapActions } from 'vuex'

    export default {

        props: {
            value: {
                type: Object
            },
            month: {
                type: String
            }
        },

        computed: {
            defaultStatusClasses () {
                return 'bg-gray-300 border-gray-300'
            },
            icon () {
                if (this.value.cleared) {
                    return 'check'
                } else if (this.value.paid) {
                    return 'dotsHorizontal'
                } else if (this.value.goal) {
                    return 'dotsHorizontal'
                } else {
                    return 'dotsHorizontal'
                }
            },
            label () {
                if (this.value.cleared) {
                    return 'cleared'
                } else if (this.value.paid) {
                    return 'paid'
                } else if (this.value.goal) {
                    return 'saved'
                } else {
                    return 'none'
                }
            },
            variant () {
                if (this.value.cleared) {
                    return 'success'
                } else if (this.value.paid) {
                    return 'warning'
                } else if (this.value.goal) {
                    return 'danger'
                } else {
                    return 'secondary'
                }
            }
        },

        methods: {
            ...mapActions(['updateBudgetEntry', 'removeEntryFromTransaction', 'refreshIncomeStats']),

            async update () {
                const entry = this.value

                if (entry.cleared) {
                    // Advance to none
                    entry.goal = false
                    entry.paid = false
                    entry.cleared = false
                    await this.updateState({ goal: false })
                    await this.refreshIncomeStats()
                } else if (entry.paid) {
                    // Advance to cleared
                    entry.goal = true
                    entry.paid = true
                    entry.cleared = true
                    await this.updateState({ cleared: true })
                    await this.refreshIncomeStats()
                } else if (entry.goal) {
                    // Advance to paid
                    entry.goal = true
                    entry.paid = true
                    entry.cleared = false
                    await this.updateState({ paid: true })
                } else {
                    // Advance to goal
                    entry.goal = true
                    entry.paid = false
                    entry.cleared = false
                    await this.updateState({ goal: true })
                }
            },
            async updateState (status) {
                try {
                    if (!status.cleared) {
                        // Since the status is not cleared we need to remove the transaction
                        // since an entry cannot be cleared and be linked to a transaction
                        //ToDo: We need to have a modal confirming that the transaction will be un-linked
                        status.bankTransactionId = null
                    }
                    const { data: entry } = await this.$http.updateEntry(this.month, this.value.id, status, 'income,group,transactions')
                    if (entry.cleared !== true) {
                        entry.bankTransactionId = null
                    }
                    await this.updateBudgetEntry(entry)
                    await this.removeEntryFromTransaction(entry)
                    this.$emit('updated', entry)
                } catch ({ error }) {
                    console.log('error', error)
                }
            }
        }
    }

</script>
