<template>
    <div class="ui blue colored segment">
        <span class="ui blue ribbon label">{{ filterRibbon }}</span>
        <div class="ui middle aligned grid">
            <div class="sixteen wide mobile two wide tablet two wide computer centered right aligned column">
                <div :id="'isUrgent'+_uid" class="ui checkbox filter">
                    <input type="checkbox" name="isUrgent">
                    <label> <span class="ui red horizontal label">{{ urgentLabel }}</span></label>
                </div>
            </div>
            <div class="sixteen wide mobile twelve wide tablet twelve wide computer center aligned column price">
                <range-filter
                        :mini="dataMinPrice"
                        :maxi="dataMaxPrice"
                        :handle-min="dataHandleMin"
                        :handle-max="dataHandleMax"
                        :update="dataUpdate"
                        name="price"
                        :prefix="filterPricePrefix"
                ></range-filter>
            </div>
        </div>
        <div class="ui grid">
            <div class="doubling two column row">
                <div class="column">
                    <search-filter
                            :route-search="routeSearch"
                            :min-length-search="minLengthSearch"
                            :place-holder="searchPlaceHolder"
                            :results-for="dataResultsFor"
                            :update="dataUpdate"
                            :flag-reset="flagResetSearch">
                    </search-filter>
                </div>
                <div class="column">
                    <location-filter
                            :accurate-list="locationAccurateList"
                            :update="dataUpdate"
                            :place-holder="locationPlaceHolder"
                    ></location-filter>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            //vue routes
            //vue vars
            update: {
                type: Boolean
            },
            filter: {
                type: Object
            },
            //vue strings
            filterRibbon: {
                type: String
            },
            urgentLabel: {
                type: String
            },
            //range component
            filterPricePrefix: {
                type: String
            },
            //search component
            routeSearch: {
                type: String
            },
            minLengthSearch: {
                type: Number
            },
            flagResetSearch: {
                type: Boolean
            },
            searchPlaceHolder: {
                type: String
            },
            //location component
            locationAccurateList: {
                type: Array
            },
            locationPlaceHolder: {
                type: String
            }
        },
        data: () => {
            return {
                isUrgent: false,
                dataMinPrice: 0,
                dataMaxPrice: 0,
                dataHandleMin: 0,
                dataHandleMax: 0,
                dataResultsFor: '',
                dataUpdate: false
            };
        },
        mounted () {
            this.$watch('update', function () {
                this.setIsUrgent();
                this.setRangeFilter();
                this.setSearchFilter();
                this.dataUpdate = !this.dataUpdate;
            });
            this.$on('rangeUpdate', function (event) {
                if(event.name == 'price'){
                    this.$parent.$emit('updateFilter', {'minPrice' : event.values[0], 'maxPrice': event.values[1]});
                }
            });
            this.$watch('isUrgent', function () {
                this.$parent.$emit('updateFilter', {'isUrgent' : this.isUrgent})
            });
            this.$on('locationUpdate', function (event) {
                this.$parent.$emit('updateFilter', event)
            });
            this.$on('refreshResults', function (query) {
                this.$parent.$emit('refreshResults', query);
            });
            this.$on('clearSearchResults', function () {
                this.$parent.$emit('clearSearchResults');
            });
            this.$on('clearLocationResults', function () {
                this.$parent.$emit('clearLocationResults');
            });
            let that = this;
            let isUrgent = $('#isUrgent'+this._uid);
            isUrgent.checkbox({
                onChecked: function() {that.isUrgent = true;},
                onUnchecked: function() {that.isUrgent = false;}
            });
        },
        methods: {
            setIsUrgent: function () {
                let isUrgent = $('#isUrgent'+this._uid);
                if (this.filter.isUrgent != undefined && this.filter.isUrgent == true ) {
                    isUrgent.checkbox('set checked');
                    this.isUrgent = true;
                } else {
                    isUrgent.checkbox('set unchecked');
                    this.isUrgent = false;
                }
            },
            setRangeFilter: function () {
                this.dataMinPrice = this.filter.minRangePrice;
                this.dataMaxPrice = this.filter.maxRangePrice;
                this.dataHandleMin = this.filter.minPrice;
                this.dataHandleMax = this.filter.maxPrice;
            },
            setSearchFilter: function () {
                this.dataResultsFor = this.filter.resultsFor;
            }
        }
    }
</script>
