<template>
    <img :class="classes" :src="avatar" :alt="fullName"/>
</template>

<script>
    export default {

        props: {
            size: {
                type: [String, Number],
                default: 8
            },
            rounded: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            avatar () {
                return this.$store.getters.avatar
            },
            fullName () {
                return this.$store.getters.fullName
            },
            classes () {
                const classes = [`h-${this.size} w-${this.size}`]

                if (this.rounded) {
                    classes.push('rounded-full')
                }

                return classes
            }
        }
    }

</script>
