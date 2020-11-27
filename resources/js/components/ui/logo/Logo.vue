<template>
    <img src="/assets/favicons/favicon-32x32.png" :class="classes" alt=""></template>
</template>

<script>
    export default {

        props: {
            size: {
                type: String,
                default: 'md',
                validator: function (value) {
                    // The value must match one of these strings
                    return ['xs', 'sm', 'md', 'lg', 'xl'].indexOf(value) !== -1
                }
            }
        },

        computed: {
            classes () {
                const classes = [];

                if (this.size === 'xs') classes.push('w-8 h-8')
                if (this.size === 'sm') classes.push('w-10 h-10')
                if (this.size === 'md') classes.push('w-12 h-12')
                if (this.size === 'lg') classes.push('w-16 h-16')
                if (this.size === 'xl') classes.push('w-20 h-20')

                return classes
            }
        }
    }
</script>

<style lang="scss" scoped>
    img.logo-size-xs {

    }
    img.logo-size-sm {

    }
    img.logo-size-md {

    }
    img.logo-size-lg {

    }
    img.logo-size-xl {

    }
</style>
