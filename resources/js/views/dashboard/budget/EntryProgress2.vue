<template>
    <!-- Added @mousedown.prevent=" to this element to prevent the browser from selected text on page when clicking fast -->
    <div class="cursor-pointer shadow rounded-md m-1" @click="update" @mousedown.prevent="">
        <div class="px-3 py-2 mt-2 text-xs text-center border rounded-md" :class="classes">
            <icon :name="icon" size="4" class="text-gray-700"></icon>
        </div>
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
                return 'save'
            } else if (this.value.goal) {
                return 'save'
            } else {
                return 'dotsHorizontal'
            }
        },
        classes () {
            if (this.value.cleared) {
                return 'bg-green-300 border-green-300'
            } else if (this.value.paid) {
                return 'bg-yellow-300 border-yellow-300'
            } else if (this.value.goal) {
                return 'bg-red-300 border-red-300'
            } else {
                return this.defaultStatusClasses
            }
        }
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
