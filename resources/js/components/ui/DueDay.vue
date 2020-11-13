<template>
  <span>
    {{ dueDay }}
  </span>
</template>

<script>
  import moment from 'moment'

  export default {
    props: {
      value: null
    },
    computed: {
      dueDay() {
        if (moment.isDate(this.value)) {
          return moment(this.value).format('Do')
        } else if (this.value) {
          return moment().date(this.value).format('Do')
        }
        return ''
      }
    }
  }
</script>
