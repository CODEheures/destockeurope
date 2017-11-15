<template></template>
<script>
    export default {
        props: {
            properties: {
                type: String
            }
        },
        created () {
            this.$store.commit('setProperties', {'name': (JSON.parse(this.properties)).key, 'properties': (JSON.parse(this.properties)).values})
        }
    }
</script>