<template>
    <div class="ui blue colored segment">
        <div :id="'filter-accordion-'+_uid" class="ui fluid accordion filter">
            <div class="active title">
                <span class="ui blue ribbon label"><i class="dropdown icon"></i><span class="close">{{ strings.ribbonClose }}</span><span class="open">{{ strings.ribbonOpen }}</span></span>
                <breadcrumb
                        :items="breadcrumbItems"
                        :withAction="true"
                ></breadcrumb>
            </div>
            <div class="active content">
                <div class="ui grid">
                    <div class="sixteen wide mobile only sixteen wide tablet only column">
                        <categories-dropdown-menu
                                :old-choice="categoryOldChoice"
                                :with-all="true"
                        ></categories-dropdown-menu>
                    </div>
                </div>
                <div class="ui middle aligned grid">
                    <template v-if="currenciesList.length > 1">
                        <div class="sixteen wide mobile nine wide computer center aligned column price">
                            <div class="ui grid">
                                <div class="fourteen wide column">
                                    <range-filter
                                            :mini="dataMinPrice"
                                            :maxi="dataMaxPrice"
                                            :handle-min="dataHandleMinPrice"
                                            :handle-max="dataHandleMaxPrice"
                                            :step="0.01"
                                            :update="dataUpdate"
                                            name="price"
                                            :prefix="filterPricePrefix"
                                            :title="strings.priceTitle"
                                    ></range-filter>
                                </div>
                                <div class="two wide column">
                                    <currencies-button
                                            :currencies-list="currenciesList"
                                            :oldCurrency="filter.currency"
                                            :withAll="true">
                                    </currencies-button>
                                </div>
                            </div>
                        </div>
                        <div class="sixteen wide mobile seven wide computer center aligned column price">
                            <range-filter
                                    :mini="dataMinQuantity"
                                    :maxi="dataMaxQuantity"
                                    :handle-min="dataHandleMinQuantity"
                                    :handle-max="dataHandleMaxQuantity"
                                    :update="dataUpdate"
                                    name="quantity"
                                    prefix=""
                                    :title="strings.quantityTitle"
                            ></range-filter>
                        </div>
                    </template>
                    <template v-else>
                        <div class="sixteen wide mobile eight wide computer center aligned column price">
                            <range-filter
                                    :mini="dataMinPrice"
                                    :maxi="dataMaxPrice"
                                    :handle-min="dataHandleMinPrice"
                                    :handle-max="dataHandleMaxPrice"
                                    :step="0.01"
                                    :update="dataUpdate"
                                    name="price"
                                    :prefix="filterPricePrefix"
                                    :title="strings.priceTitle"
                            ></range-filter>
                        </div>
                        <div class="sixteen wide mobile eight wide computer center aligned column price">
                            <range-filter
                                    :mini="dataMinQuantity"
                                    :maxi="dataMaxQuantity"
                                    :handle-min="dataHandleMinQuantity"
                                    :handle-max="dataHandleMaxQuantity"
                                    :update="dataUpdate"
                                    name="quantity"
                                    prefix=""
                                    :title="strings.quantityTitle"
                            ></range-filter>
                        </div>
                    </template>
                </div>
                <div class="ui grid">
                    <div class="doubling two column row">
                        <div class="column">
                            <search-filter
                                    :route-search="routeSearch"
                                    :place-holder="strings.searchPlaceHolder"
                                    :results-for="dataResultsFor"
                                    :update="dataUpdate"
                                    :flag-reset="flagResetSearch"
                                    :fields="{title: 'titleWithManuRef', description : 'resume', image: 'thumb', price: 'price_margin'}"
                            ></search-filter>
                        </div>
                        <div class="column">
                            <location-filter
                                    :accurate-list="locationAccurateList"
                                    :update="dataUpdate"
                            ></location-filter>
                        </div>
                    </div>
                </div>
                <div class="ui stackable two column grid">
                    <div class="column">
                        <div class="ui grid">
                            <div class="eight wide column">
                                <div :id="'isUrgent'+_uid" class="ui checkbox filter">
                                    <input type="checkbox" name="isUrgent">
                                    <label> <span class="ui red horizontal label">{{ strings.urgentLabel }}</span></label>
                                </div>
                            </div>
                            <div class="eight wide column">
                                <div :id="'isNegociated'+_uid" class="ui checkbox filter">
                                    <input type="checkbox" name="isegociated">
                                    <label> <span class="ui blue horizontal label">{{ strings.isNegociatedLabel }}</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <notifications-activer
                                :route-exist-in="routeNotificationsExistIn"
                                :route-add="routeNotificationsAdd"
                                :route-remove="routeNotificationsRemove"
                                :topic_id="parseInt(1)"
                        ></notifications-activer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            //vue routes
            routeNotificationsExistIn: String,
            routeNotificationsAdd: String,
            routeNotificationsRemove: String,
            //vue vars
            breadcrumbItems: {
                type: Array
            },
            update: {
                type: Boolean
            },
            filter: {
                type: Object
            },
            filterPricePrefix: {
                type: String
            },
            routeSearch: {
                type: String
            },
            locationAccurateList: {
                type: Array
            },
            flagResetSearch: {
                type: Boolean
            },
            categoryOldChoice: {
                type: Number,
                required: false,
                default: 0
            },
            currenciesList: {
                type: Array,
                required: false,
                default: []
            }
        },
        data: () => {
            return {
                strings: {},
                properties: {},
                isUrgent: false,
                isNegociated: false,
                dataMinPrice: 0,
                dataMaxPrice: 0,
                dataHandleMinPrice: 0,
                dataHandleMaxPrice: 0,
                dataMinQuantity: 0,
                dataMaxQuantity: 0,
                dataHandleMinQuantity: 0,
                dataHandleMaxQuantity: 0,
                dataResultsFor: '',
                dataUpdate: false
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['advert-filter'];
            this.properties = this.$store.state.properties['global'];
            this.$watch('update', function () {
                this.setIsUrgent();
                this.setIsNegociated();
                this.setRangeFilter();
                this.setSearchFilter();
                this.dataUpdate = !this.dataUpdate;
            });
            this.$on('rangeUpdate', function (event) {
                if(event.name == 'price'){
                    this.$parent.$emit('updateFilter', {'minPrice' : event.values[0], 'maxPrice': event.values[1]});
                }
                if(event.name == 'quantity'){
                    this.$parent.$emit('updateFilter', {'minQuantity' : event.values[0], 'maxQuantity': event.values[1]});
                }
            });
            this.$watch('isUrgent', function () {
                this.$parent.$emit('updateFilter', {'isUrgent' : this.isUrgent})
            });
            this.$watch('isNegociated', function () {
                this.$parent.$emit('updateFilter', {'isNegociated' : this.isNegociated, 'minPrice': 0})
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
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', event);
            });
            this.$on('currencyChoice', function (event) {
                this.$parent.$emit('updateFilter', {'currency' : event.cur});
            });
            let that = this;
            let isUrgent = $('#isUrgent'+this._uid);
            isUrgent.checkbox({
                onChecked: function() {that.isUrgent = true;},
                onUnchecked: function() {that.isUrgent = false;}
            });
            let isNegociated = $('#isNegociated'+this._uid);
            isNegociated.checkbox({
                onChecked: function() {that.isNegociated = true;},
                onUnchecked: function() {that.isNegociated = false;}
            });

            let accordionElement = $('#filter-accordion-'+this._uid);
            if($(window).width()<768){
                accordionElement.accordion('close',0);
            } else {
                accordionElement.accordion();
            }
        },
        updated () {
            //$('filter-accordion-'+this._uid).accordion();
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
            setIsNegociated: function () {
                let isNegociated = $('#isNegociated'+this._uid);
                if (this.filter.isNegociated != undefined && this.filter.isNegociated == true ) {
                    isNegociated.checkbox('set checked');
                    this.isNegociated = true;
                } else {
                    isNegociated.checkbox('set unchecked');
                    this.isNegociated = false;
                }
            },
            setRangeFilter: function () {
                this.dataMinPrice = this.filter.minRangePrice;
                this.dataMaxPrice = this.filter.maxRangePrice;
                this.dataHandleMinPrice = this.filter.minPrice;
                this.dataHandleMaxPrice = this.filter.maxPrice;
                this.dataMinQuantity = this.filter.minRangeQuantity;
                this.dataMaxQuantity = this.filter.maxRangeQuantity;
                this.dataHandleMinQuantity = this.filter.minQuantity;
                this.dataHandleMaxQuantity = this.filter.maxQuantity;
            },
            setSearchFilter: function () {
                this.dataResultsFor = this.filter.resultsFor;
            }
        }
    }
</script>
