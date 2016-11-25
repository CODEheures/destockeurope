<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="mobile only tablet only row">
            <div class="ui one column grid">
                <categories-dropdown-menu
                        :route-category="routeCategory"
                        :first-menu-name="categoriesDropdownMenuFirstMenuName"
                        :actual-locale="actualLocale"
                        :old-choice="filter.categoryId"
                        :with-all="true"
                        :all-item="categoriesAllItem">
                </categories-dropdown-menu>
            </div>
        </div>
        <div class="row">
            <div class="computer only sixteen wide column">
                <breadcrumb
                    :items="breadcrumbItems">
                </breadcrumb>
            </div>
            <div :id="'column1_'+_uid" class="computer only four wide column">
                <div>
                    <categories-lateral-accordion-menu
                            :route-category="routeCategory"
                            :actual-locale="actualLocale"
                            :all-item="categoriesAllItem">
                    </categories-lateral-accordion-menu>
                    <div class="ui small rectangle test ad welcome-ads" data-text="Small Rectangle"></div>
                    <div class="ui small rectangle test ad welcome-ads" data-text="Small Rectangle"></div>
                    <!--<div class="ui wide skyscraper test ad welcome-ads" data-text="Wide Skyscraper"></div>-->
                </div>
            </div>
            <div class="sixteen wide tablet twelve wide computer column">
                <div class="row filters">
                    <advert-filter
                        :filter-ribbon="filterRibbon"
                        :urgent-label="filterUrgentLabel"
                        :min-price="minPrice"
                        :max-price="maxPrice"
                        :filter-price-title ="filterPriceTitle"
                        :route-search="dataRouteGetAdvertList"
                        :min-length-search="parseInt(filterMinLengthSearch)"
                        :flag-reset-search="dataFlagResetSearch"
                        :search-place-holder="filterSearchPlaceHolder">
                    </advert-filter>
                </div>
                <div class="row">
                    <adverts-by-list
                            :route-get-adverts-list="dataRouteGetAdvertList"
                            :ads-frequency="parseInt(adsFrenquency)"
                            :actual-locale="actualLocale"
                            :total-quantity-label="totalQuantityLabel"
                            :lot-mini-quantity-label="lotMiniQuantityLabel"
                            :urgent-label="urgentLabel"
                            :price-info-label="priceInfoLabel"
                            :no-result-found-header="noResultFoundHeader"
                            :no-result-found-message="noResultFoundMessage">
                    </adverts-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                            :pages="paginate"
                            :route-get-advert-list="dataRouteGetAdvertList"
                            :page-label="pageLabel"
                            :page-previous-label="pagePreviousLabel"
                            :page-next-label="pageNextLabel">
                        </pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            //vue routes
            //vue vars
            'clearStorage',
            //vue strings
            'loadErrorMessage',
            //category dropdown menu component
            'routeCategory',
            'categoriesDropdownMenuFirstMenuName',
            'categoriesAllItem',
            'actualLocale',
            //filter advert component
            'filterMinLengthSearch',
            'filterRibbon',
            'filterPriceTitle',
            'filterUrgentLabel',
            'filterSearchPlaceHolder',
            //advertByList component
            'routeGetAdvertsList',
            'adsFrenquency',
            'totalQuantityLabel',
            'lotMiniQuantityLabel',
            'urgentLabel',
            'priceInfoLabel',
            'noResultFoundHeader',
            'noResultFoundMessage',
            //paginate component
            'pageLabel',
            'pagePreviousLabel',
            'pageNextLabel'
        ],
        data: () => {
            return {
                typeMessage : '',
                message : '',
                sendMessage: false,
                breadcrumbItems: [],
                filter: {categoryId: 0},
                paginate: {},
                dataRouteGetAdvertList: '',
                minPrice: 0,
                maxPrice: 0,
                dataFlagResetSearch: false
            }
        },
        mounted () {
            //Visibility for ADS
            var elem = $('#column1_'+this._uid).children('div');
            elem.visibility({
                type   : 'fixed',
                offset : 112
            });

            //Init dataRoute
            if(sessionStorage.getItem('goToCategory') != undefined){
                this.choiceCategory(sessionStorage.getItem('goToCategory'),true);
                this.dataRouteGetAdvertList = this.urlForFilter(true, true);
                this.dataRouteGetAdvertList = this.urlForFilter();
                sessionStorage.removeItem('goToCategory');
            } else {
                this.dataRouteGetAdvertList = this.urlForFilter(false, true);
            }

            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });

            var that = this;
            this.$on('categoryChoice', function (event) {
                if(event.id != undefined && event.id > 0) {
                    if(parseInt(event.id) != this.filter.categoryId) {
                        this.choiceCategory(event.id)
                    }
                } else {
                    if(this.filter.categoryId != 0){
                        this.choiceCategory(0)
                    } else {
                        let haveClearAction = this.clearInputSearch();
                        if(haveClearAction){
                            this.updateResults(true);
                        }
                    }
                }
            });
            this.$on('paginate', function (result) {
                this.paginate=result;
            });
            this.$on('updateFilter', function (result) {
                this.updateFilter(result);
            });
            this.$on('changePage', function (url) {
                $('html, body').animate({
                    scrollTop: 0
                }, 600, function () {
                    that.dataRouteGetAdvertList = url;
                });
            });
            this.$on('setRangePrice', function (prices) {
                this.setRangePrice(prices);
            });
            this.$on('refreshResults', function (query) {
                if(query != undefined && query.length >= this.filterMinLengthSearch){
                    this.filter.resultsFor = query;
                    this.updateResults(true);
                }
            });
            this.$on('clearSearchResults', function () {
                let haveClearAction = this.clearInputSearch();
                if(haveClearAction){
                    this.updateResults(true);
                }
            });
            if(this.clearStorage){
                sessionStorage.clear();
            }
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            setBreadCrumbItems: function (categoryId) {
                this.breadcrumbItems = [];
                var that = this;
                if(categoryId != undefined && categoryId>0 ) {
                    this.$http.get(this.routeCategory+'/'+categoryId)
                            .then(
                                    function (response) {
                                        var chainedCategories = response.data;
                                        for(var index in chainedCategories){
                                            that.breadcrumbItems.push({
                                                name: chainedCategories[index]['description'][that.actualLocale],
                                                value: chainedCategories[index].id
                                            });
                                        }
                                    },
                                    function (response) {
                                        that.breadcrumbItems.push({
                                            name: this.loadErrorMessage,
                                            value:''
                                        });
                                    }
                            );
                }
            },
            urlForFilter(priceOnly=false, init=false) {
                let urlBase = init ? this.routeGetAdvertsList : this.dataRouteGetAdvertList;
                let parsed = Parser.parse(urlBase, true);
                parsed.search=undefined;
                parsed.query={};
                for(var elem in this.filter){
                    parsed.query[elem]=(this.filter[elem]).toString();
                }
                if(priceOnly){
                    parsed.query.priceOnly=true.toString();
                } else {
                    if("priceOnly" in parsed.query){
                        delete parsed.query.priceOnly;
                    }
                }
                return Parser.format(parsed);
            },
            setRangePrice(prices) {
                this.minPrice = prices.mini;
                this.maxPrice = prices.maxi;
            },
            updateRangePrices(url) {
                this.isLoaded = false;
                var that = this;
                this.$http.get(url)
                        .then(
                                function (response)  {
                                    that.isLoaded = true;
                                    that.setRangePrice({'mini': parseFloat((response.data).minPrice), 'maxi': parseFloat((response.data).maxPrice)});
                                },
                                function (response)  {
                                    that.$parent.$emit('loadError')
                                }
                        );
            },
            clearInputSearch() {
                if('resultsFor' in this.filter) {
                    delete this.filter.resultsFor;
                    this.dataFlagResetSearch = !this.dataFlagResetSearch;
                    return true;
                } else {
                    return false;
                }
            },
            choiceCategory(categoryId, choiceOnMounted=false) {
                this.setBreadCrumbItems(categoryId);
                this.filter.categoryId = parseInt(categoryId);
                this.clearInputSearch();
                if(!choiceOnMounted){
                    this.updateResults(true);
                }
            },
            updateResults(withUpdatePriceFilter=false){
                if(withUpdatePriceFilter) {
                    let urlGetRangePrices = this.urlForFilter(true);
                    this.updateRangePrices(urlGetRangePrices);
                } else {
                    this.dataRouteGetAdvertList = this.urlForFilter();
                }
            },
            updateFilter(result){
                for(let elem in result){
                    this.filter[elem] = result[elem];
                }
                this.updateResults(false); //Attention jamais true param sinon boucle infinie
            }
        }
    }
</script>
