<template></template>
<script>
    export default {
        props: {
            properties: {
                type: String
            }
        },
        data: () => {
            return {
                dataProps: {}
            };
        },
        mounted () {
            this.dataProps = JSON.parse(this.properties);
            this.$store.commit('setProperties', {'name': this.dataProps.key, 'properties': this.dataProps.values});
        },
        methods: {

        }
    }
</script>