<template>
    <div class="ui blue colored segment">
        <span class="ui blue ribbon label">{{ filterRibbon }}</span>
        <p>{{ filterPriceTitle }}</p>
        <range-filter
            :mini="0"
            :maxi="100"
            name="price">
        </range-filter>
    </div>
</template>


<script>
    export default {
        props: [
            'filterRibbon',
            'filterPriceTitle'
        ],
        data: () => {
            return {

            };
        },
        mounted () {
            this.$on('rangeUpdate', function (event) {
                if(event.name == 'price'){
                    this.updateFilterPrice(event.values);
                }
            });
        },
        methods: {
            updateFilterPrice: function (values) {

            }
        }
    }
</script>
