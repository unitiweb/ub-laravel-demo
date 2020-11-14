<template>
  <span>
    <span v-if="label !== ''" :class="labelClasses" class="label mr-1">{{ label }}:</span>
    <span :class="valueClass"><slot>{{ currencyValue }}</slot></span>
  </span>
</template>

<script>
  export default {
    props: {
      label: {
        type: String,
        default: ''
      },
      labelClasses: {
        type: String,
        default: ''
      },
      value: {
        type: [String, Number],
        default: 0.00
      },
      labelClass: {
        type: String,
        default: ''
      },
      valueClass: {
        type: String,
        default: ''
      }
    },
    computed: {
      currencyValue() {
        if (this.value) {
          return this.format(this.value)
        }
        return this.format(0)
      }
    },
    methods: {
      format(value) {
        const formatter = new Intl.NumberFormat('en-US', {
          style: 'currency',
          currency: 'USD',
          minimumFractionDigits: 2
        })

        return formatter.format(value)
      }
    }
  }
</script>

<style lang="scss">
  .label {
    font-weight: bold;
  }
</style>
