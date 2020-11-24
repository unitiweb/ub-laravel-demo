<template>
    <!-- Added @mousedown.prevent=" to this element to prevent the browser from selected text on page when clicking fast -->
    <div class="cursor-pointer shadow-md rounded-md m-1" @click="update" @mousedown.prevent="">
        <div class="px-3 border-t border-l border-r border-b-0 rounded-t-md" :class="clearedClasses">
            <icon name="dotsHorizontal" class="text-gray-600 h-3 w-3"></icon>
        </div>
        <div class="px-3 border-l border-r border-t border-b-0" :class="paidClasses">
            <icon name="dotsHorizontal" class="text-gray-600 h-3 w-3"></icon>
        </div>
        <div class="px-3 border rounded-b-md" :class="goalClasses">
            <icon name="dotsHorizontal" class="text-gray-600 h-3 w-3"></icon>
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
            return 'bg-gray-300 border-gray-400'
        },
        clearedClasses () {
            if (this.value.cleared) {
                return 'bg-green-300 border-green-400'
            } else {
                return this.defaultStatusClasses
            }
        },
        paidClasses () {
            if (this.value.paid) {
                return 'bg-orange-300 border-orange-400'
            } else {
                return this.defaultStatusClasses
            }
        },
        goalClasses () {
            if (this.value.goal) {
                return 'bg-red-300 border-red-400'
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
            } else if (entry.paid) {
                // Advance to cleared
                entry.goal = true
                entry.paid = true
                entry.cleared = true
            } else if (entry.goal) {
                // Advance to paid
                entry.goal = true
                entry.paid = true
                entry.cleared = false
            } else {
                // Advance to goal
                entry.goal = true
                entry.paid = false
                entry.cleared = false
            }

            this.updateState({
                goal: entry.goal,
                paid: entry.paid,
                cleared: entry.cleared
            })
        },
        updateState ({ goal, paid, cleared }) {
            this.$http.updateEntry(this.month, this.value.id, { goal, paid, cleared })
                .then(({ data }) => {
                    this.$emit('updated', data)
                }).catch(({ error }) => {
                    console.log('error', error)
                })
        }
    }

}

</script>
