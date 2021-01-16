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
        },
        // classes () {
        //     if (this.value.cleared) {
        //         return 'bg-green-300 border-green-300'
        //     } else if (this.value.paid) {
        //         return 'bg-yellow-300 border-yellow-300'
        //     } else if (this.value.goal) {
        //         return 'bg-red-300 border-red-300'
        //     } else {
        //         return this.defaultStatusClasses
        //     }
        // }
    },

    methods: {
        update () {
            const entry = this.value

            if (entry.cleared) {
                // Advance to none
                entry.goal = false
                entry.paid = false
                entry.cleared = false
                this.updateState({ goal: false })
            } else if (entry.paid) {
                // Advance to cleared
                entry.goal = true
                entry.paid = true
                entry.cleared = true
                this.updateState({ cleared: true })
            } else if (entry.goal) {
                // Advance to paid
                entry.goal = true
                entry.paid = true
                entry.cleared = false
                this.updateState({ paid: true })
            } else {
                // Advance to goal
                entry.goal = true
                entry.paid = false
                entry.cleared = false
                this.updateState({ goal: true })
            }
        },
        updateState (data) {
            this.$http.updateEntry(this.month, this.value.id, data)
                .then(({ data }) => {
                    this.$emit('updated', data)
                }).catch(({ error }) => {
                    console.log('error', error)
                })
        }
    }

}

</script>
