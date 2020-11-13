<template>
    <t-alert :variant="variant" :dismissible="dismissible" :show="show" :timeout="getTimeout">
        <div v-if="title" class="font-bold mb-2">{{ title }}</div>
        <icon v-if="icon" :name="icon" variant="outline" class="float-left h-5 w-5 mr-2"></icon>
        <slot></slot>
    </t-alert>
</template>

<script>

export default {

    props: {
        show: {
            type: Boolean,
            default: false
        },
        title: {
            type: String
        },
        variant: {
            type: String,
            default: 'clean',
            validator: value => {
                return ['success', 'warning', 'danger', 'clean'].indexOf(value) !== -1
            }
        },
        icon: {
            type: String
        },
        dismissible: {
            type: Boolean,
            default: false
        },
        timeout: {
            type: Number
        }
    },

    computed: {
        getTimeout () {
            if (this.timeout) {
                return this.timeout * 1000
            }
        }
    }

}

</script>
