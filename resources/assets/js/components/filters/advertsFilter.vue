<template>
    <div class="ui teal colored segment">
        <span class="ui teal ribbon label">{{ filterRibbon }}</span>
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
                //console.log(values);
            }
        }
    }
</script>
