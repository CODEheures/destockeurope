<template></template>
<script>
  /**
   * To store strings on the $store by side-server parameters (use to exploit trans(), env(), config() etc etc...)
   * Props
   *  - strings: String. Json of the strings: "{key: 'advert-filter', values: {...}}"
   *
   * Events:
   *
   */
  export default {
    props: {
      strings: String
    },
    created () {
      this.$store.commit('setStrings', {'name': (JSON.parse(this.strings)).key, 'strings': (JSON.parse(this.strings)).values})
    }
  }
</script>