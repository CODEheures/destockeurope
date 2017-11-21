<template></template>
<script>
  /**
   * To store properties on the $store by side-server parameters (use to exploit trans(), env(), config() etc etc...)
   * Props
   *  - properties: String. Json of the properties: "{key: 'global', values: {...}}"
   *
   * Events:
   *
   */
  export default {
    props: {
      properties: String
    },
    created () {
      this.$store.commit('setProperties', {'name': (JSON.parse(this.properties)).key, 'properties': (JSON.parse(this.properties)).values})
    }
  }
</script>