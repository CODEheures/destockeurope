<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="mobile only tablet only row">
            <div class="ui one column grid">
                <categories-dropdown-menu
                        :route-category="routeCategory"
                        :first-menu-name="categoriesDropdownMenuFirstMenuName"
                        :actual-locale="actualLocale"
                        :old-choice="filter.id"
                        :with-all="true"
                        :all-item="categoriesAllItem">
                </categories-dropdown-menu>
            </div>
        </div>
        <div class="computer only sixteen wide column">
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
                    <price-advert-filter
                            :filter-ribbon="filterRibbon"
                            :filter-price-title ="filterPriceTitle">
                    </price-advert-filter>
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
                            :price-info-label="priceInfoLabel">
                    </adverts-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                            :pages="paginate"
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
            //price advert component
            'filterRibbon',
            'filterPriceTitle',
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
                filter: {'id' : 0},
                paginate: {},
                dataRouteGetAdvertList: ''
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
                    if(parseInt(event.id) != this.filter['id']) {
                        this.setBreadCrumbItems(event.id);
                        this.filter['id'] = parseInt(event.id);
                    }
                } else {
                    this.breadcrumbItems= [];
                    this.filter['id'] = 0;
                }
            });
            this.$on('paginate', function (result) {
                this.paginate=result;
            });
            var that = this;
            this.$on('changePage', function (url) {
                $('html, body').animate({
                    scrollTop: 0
                }, 600, function () {
                    that.dataRouteGetAdvertList = url;
                });

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

            }
        }
    }
</script>