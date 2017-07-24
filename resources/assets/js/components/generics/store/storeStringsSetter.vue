<template></template>
<script>
    export default {
        props: {
            strings: {
                type: String
            }
        },
        data: () => {
            return {
                dataStrings: {}
            };
        },
        mounted () {
            this.dataStrings = JSON.parse(this.strings);
            this.$store.commit('setStrings', {'name': this.dataStrings.key, 'strings': this.dataStrings.values});
        },
        methods: {

        }
    }
</script>