<template>
    <img :class="classes" :src="avatar" :alt="fullName"/>
</template>

<script>
    import config from "@/config";

    export default {

        props: {
            filename: {
                type: String,
                default: null
            },
            size: {
                type: [String, Number],
                default: 8
            },
            rounded: {
                type: Boolean,
                default: false
            },
        },

        computed: {
            avatar () {
                let path = this.filename
                if (!path) {
                    path = this.$store.getters.user.avatar || null
                }

                if (path) {
                    return `${config.AVATAR_BASE_PATH}/${path}`
                }

                return '/assets/static/img/no-photo.png'
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
