<template>
    <div class="cursor-text">
        <div v-if="!edit" :class="classes" @click="toggle">
            <currency v-if="currency" :value="value"/>
            <span v-if="!currency">{{ current }}</span>
        </div>
        <input v-if="edit"
               ref="editInPlaceInput"
               class="border border-gray-500 focus:border-gray-500 active:border-gray-500 rounded-md"
               autofocus
               :class="classes"
               :style="styles"
               :value="current"
               @input="input"
               @blur="update"
               @keyup.enter="update"
               @keyup.esc="cancel"
        />
    </div>
</template>

<script>
    import Currency from '@/components/ui/Currency'
    import { cleanCurrency } from '@/scripts/helpers/utils'

    export default {

        components: {
            Currency
        },

        props: {
            value: {
                type: [String, Number],
                default: ''
            },
            classes: {
                type: [String, Array],
                default: ''
            },
            currency: {
                type: Boolean,
                default: false
            },
            selectOnEdit: {
                type: Boolean,
                default: false
            },
            width: {
                type: [String, Number]
            }
        },

        data () {
            return {
                edit: this.edit,
                original: ''
            }
        },

        computed: {
            current () {
                if (this.currency) {
                    return cleanCurrency(this.value)
                }
                return this.value
            },
            styles () {
                const styles = {}

                if (this.width) {
                    styles.width = `${this.width}px`
                }

                return styles
            }
        },

        methods: {
            toggle () {
                this.edit = !this.edit
                // If we done use $nextTick the this.$refs.editInPlaceInput will not be defined right when
                // the element is clicked
                this.$nextTick(() => {
                    const element = this.$refs.editInPlaceInput
                    element.focus()
                    if (this.selectOnEdit) {
                        element.select()
                    }
                });
            },
            input (e) {
                let value = e.target.value
                this.$emit('input', value)
            },
            update () {
                this.$emit('updated')
                this.edit = false
            },
            cancel () {
                this.$emit('input', this.original)
                this.edit = false
            }
        },

        mounted () {
            this.original = this.current
        }

    }

</script>
