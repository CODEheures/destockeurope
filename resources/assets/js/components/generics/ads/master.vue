<template>
    <div v-if="dataIsActive">
        <img class="ui fluid image masterads" src="/images/background.jpg">
    </div>
</template>

<script>
    export default {
        props: [
            'isActive'
        ],
        data: () => {
            return {
                dataIsActive: false
            }
        },
        mounted () {
            console.log(this.isActive);
            this.dataIsActive = this.isActive == '1';
        },
        methods: {

        }
    }
</script>
