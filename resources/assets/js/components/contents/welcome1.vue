<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="mobile only row">
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
        <div class="tablet only computer only sixteen wide column">
            <div class="row">
                <breadcrumb
                        :items="breadcrumbItems">
                </breadcrumb>
            </div>
            <div class="row">
                <categories-horizontal-menu
                        :route-category="routeCategory"
                        :actual-locale="actualLocale"
                        :all-item="categoriesAllItem">
                </categories-horizontal-menu>
            </div>
        </div>
        <div class="row">
            <div class="sixteen wide column">
                <div class="row filters">
                    <advert-filter
                            :filter-ribbon="filterRibbon"
                            :urgent-label="filterUrgentLabel"
                            :min-price="minPrice"
                            :max-price="maxPrice"
                            :filter-price-title ="filterPriceTitle"
                            :route-search="dataRouteGetAdvertList">
                    </advert-filter>
                </div>
            </div>
            <div class="sixteen wide tablet twelve wide computer column">
                <div class="row">
                    <adverts-by-list
                            :route-get-adverts-list="dataRouteGetAdvertList"
                            :route-get-thumb="routeGetThumb"
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
            <div id="welcome-ads" class="computer only four wide column">
                <div>
                    <div class="sixteen right aligned column">
                        <div class="ui small rectangle centered test ad" data-text="Small Rectangle"></div>
                        <div class="ui small rectangle centered test ad" data-text="Small Rectangle"></div>
                        <!--<div class="ui wide skyscraper test ad welcome-ads" data-text="Wide Skyscraper"></div>-->
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
            'filterRibbon',
            'filterPriceTitle',
            'filterUrgentLabel',
            //advertByList component
            'routeGetAdvertsList',
            'routeGetThumb',
            'adsFrenquency',
            'advertTitleLabel',
            'advertDescriptionLabel',
            'advertPriceLabel',
            'seeAdvertLinkLabel',
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
                filter: {},
                paginate: {},
                dataRouteGetAdvertList: '',
                minPrice: 0,
                maxPrice: 0
            }
        },
        mounted () {
            var $elem = $('#welcome-ads').children('div');
            $elem.visibility({
                type   : 'fixed',
                offset : 112
            });
            this.dataRouteGetAdvertList = this.routeGetAdvertsList;
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.$on('categoryChoice', function (event) {
                if(event.id != undefined && event.id > 0) {
                    if(parseInt(event.id) != this.filter.categoryId) {
                        this.setBreadCrumbItems(event.id);
                        this.filter.categoryId = parseInt(event.id);
                        let urlGetRangePrices = this.urlForFilter(true);
                        this.updateRangePrices(urlGetRangePrices);
                    }
                } else {
                    if(this.filter.categoryId != 0){
                        this.breadcrumbItems= [];
                        this.filter.categoryId = 0;
                        let urlGetRangePrices = this.urlForFilter(true);
                        this.updateRangePrices(urlGetRangePrices);
                    }
                }
            });
            this.$on('paginate', function (result) {
                this.paginate=result;
            });
            this.$on('updateFilter', function (result) {
                for(let elem in result){
                    this.filter[elem] = result[elem];
                }
                this.dataRouteGetAdvertList = this.urlForFilter();
            });
            var that = this;
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
                this.$http.get(this.routeCategory+'/'+categoryId)
                        .then(
                                function (response) {
                                    var chainedCategories = response.data;
                                    this.breadcrumbItems = [];
                                    for(var index in chainedCategories){
                                        this.breadcrumbItems.push({
                                            name: chainedCategories[index]['description'][this.actualLocale],
                                            value: chainedCategories[index].id
                                        });
                                    }
                                },
                                function (response) {
                                    this.breadcrumbItems = [];
                                    this.breadcrumbItems.push({
                                        name: this.loadErrorMessage,
                                        value:''
                                    });
                                    //this.$parent.$emit('loadError');
                                }
                        );

            },
            urlForFilter(priceOnly=false) {
                let urlBase = this.dataRouteGetAdvertList;
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
            }
        }
    }
</script>
