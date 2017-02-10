<template>
    <div class="ui blue colored segment">
        <div :id="'filter-accordion-'+_uid" class="ui fluid accordion filter">
            <div class="active title">
                <span class="ui blue ribbon label"><i class="dropdown icon"></i><span class="close">{{ filterRibbonClose }}</span><span class="open">{{ filterRibbonOpen }}</span></span>
                <breadcrumb
                        :items="breadcrumbItems">
                </breadcrumb>
            </div>
            <div class="active content">
                <div class="ui grid">
                    <div class="sixteen wide mobile only sixteen wide tablet only column">
                        <categories-dropdown-menu
                                :route-category="routeCategory"
                                :first-menu-name="categoriesDropdownMenuFirstMenuName"
                                :actual-locale="actualLocale"
                                :old-choice="categoryOldChoice"
                                :with-all="true"
                                :all-item="categoriesAllItem">
                        </categories-dropdown-menu>
                    </div>
                </div>
                <div class="ui middle aligned grid">
                    <div class="sixteen wide mobile height wide computer center aligned column price">
                        <range-filter
                                :mini="dataMinPrice"
                                :maxi="dataMaxPrice"
                                :handle-min="dataHandleMinPrice"
                                :handle-max="dataHandleMaxPrice"
                                :step="0.01"
                                :update="dataUpdate"
                                name="price"
                                :prefix="filterPricePrefix"
                                :title="filterPriceTitle"
                        ></range-filter>
                    </div>
                    <div class="sixteen wide mobile height wide computer center aligned column price">
                        <range-filter
                                :mini="dataMinQuantity"
                                :maxi="dataMaxQuantity"
                                :handle-min="dataHandleMinQuantity"
                                :handle-max="dataHandleMaxQuantity"
                                :update="dataUpdate"
                                name="quantity"
                                prefix=""
                                :title="filterQuantityTitle"
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
                            <div class="ui grid">
                                <div class="ten wide column">
                                    <location-filter
                                            :accurate-list="locationAccurateList"
                                            :update="dataUpdate"
                                            :place-holder="locationPlaceHolder"
                                    ></location-filter>
                                </div>
                                <div class="six wide center aligned middle aligned column">
                                    <div :id="'isUrgent'+_uid" class="ui checkbox filter">
                                        <input type="checkbox" name="isUrgent">
                                        <label> <span class="ui red horizontal label">{{ urgentLabel }}</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <notifications-activer
                                :route-exist-in="routeNotificationsExistIn"
                                :route-add="routeNotificationsAdd"
                                :route-remove="routeNotificationsRemove"
                                :topic_id="parseInt(1)"
                                :checkbox-label="notificationsCheckboxLabel"
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
            //vue vars
            update: {
                type: Boolean
            },
            filter: {
                type: Object
            },
            //vue strings
            filterRibbonClose: {
                type: String
            },
            filterRibbonOpen: {
                type: String
            },
            urgentLabel: {
                type: String
            },
            //range component
            filterPricePrefix: {
                type: String
            },
            filterPriceTitle: {
                type: String
            },
            filterQuantityTitle: {
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
            },
            breadcrumbItems: {
                type: Array
            },
            //notification-activer component
            routeNotificationsExistIn: String,
            routeNotificationsAdd: String,
            routeNotificationsRemove: String,
            notificationsCheckboxLabel: {
                type: String
            },
            //category dropdown
            routeCategory: String,
            categoriesDropdownMenuFirstMenuName: String,
            actualLocale: String,
            categoryOldChoice: {
                type: Number,
                required: false,
                default: 0
            },
            categoriesAllItem: {
                type: String,
                required: false,
                default: ''
            }
        },
        data: () => {
            return {
                isUrgent: false,
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
                if(event.name == 'quantity'){
                    this.$parent.$emit('updateFilter', {'minQuantity' : event.values[0], 'maxQuantity': event.values[1]});
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
            this.$on('categoryChoice', function (event) {
                this.$parent.$emit('categoryChoice', event);
            });
            let that = this;
            let isUrgent = $('#isUrgent'+this._uid);
            isUrgent.checkbox({
                onChecked: function() {that.isUrgent = true;},
                onUnchecked: function() {that.isUrgent = false;}
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
