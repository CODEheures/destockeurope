<template>
    <div class="ui blue colored segment">
        <span class="ui blue ribbon label">{{ filterRibbon }}</span>
        <div class="ui middle aligned grid">
            <div class="sixteen wide mobile two wide tablet two wide computer centered right aligned column">
                <div :id="'isUrgent'+_uid" class="ui checkbox">
                    <input type="checkbox" name="isUrgent">
                    <label> <span class="ui red horizontal label">{{ urgentLabel }}</span></label>
                </div>
            </div>
            <div class="sixteen wide mobile twelve wide tablet twelve wide computer column price">
                <label class="price-label">{{ filterPriceTitle }}</label>
                <range-filter
                        :mini="minPrice"
                        :maxi="maxPrice"
                        name="price">
                </range-filter>
            </div>
        </div>
        <div class="ui grid">
            <div class="sixteen wide column">
                <search-filter
                    :route-search="routeSearch">
                </search-filter>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: [
            //vue routes
            //vue vars
            //vue strings
            'filterRibbon',
            'urgentLabel',
            //range component
            'minPrice',
            'maxPrice',
            'filterPriceTitle',
            //search component
            'routeSearch'
        ],
        data: () => {
            return {
                isUrgent: false,
            };
        },
        mounted () {
            this.$on('rangeUpdate', function (event) {
                if(event.name == 'price'){
                    this.$parent.$emit('updateFilter', {'minPrice' : event.values[0], 'maxPrice': event.values[1]});
                }
            });
            this.$watch('isUrgent', function () {
                this.$parent.$emit('updateFilter', {'isUrgent' : this.isUrgent})
            });
            var that = this;
            $('#isUrgent'+this._uid).checkbox({
                onChecked: function() {that.isUrgent = true;},
                onUnchecked: function() {that.isUrgent = false;}
            });
        },
        methods: {

        }
    }
</script>
