<template></template>
<script>
  export default {
    props: {
      strings: {
        type: String
      }
    },
    created () {
      this.$store.commit('setStrings', {'name': (JSON.parse(this.strings)).key, 'strings': (JSON.parse(this.strings)).values})
    }
  }
</script>