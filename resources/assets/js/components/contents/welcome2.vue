<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div :id="'modal2-'+_uid" class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ modalValidHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="legal icon"></i>
                </div>
                <div class="description">
                    <p>{{ modalValidDescription }}</p>
                </div>
            </div>
            <div class="actions">
                <div class="two fluid ui inverted buttons">
                    <div class="ui cancel red basic inverted button">
                        <i class="remove icon"></i>
                        {{ modalNo }}
                    </div>
                    <div class="ui ok green basic inverted button">
                        <i class="checkmark icon"></i>
                        {{ modalYes }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
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
                        :filter-ribbon-open="filterRibbonOpen"
                        :filter-ribbon-close="filterRibbonClose"
                        :breadcrumb-items="breadcrumbItems"
                        :urgent-label="filterUrgentLabel"
                        :update="update"
                        :filter="filter"
                        :filter-price-prefix="dataFilterPricePrefix"
                        :filter-price-title="filterPriceTitle"
                        :filter-quantity-title="filterQuantityTitle"
                        :route-search="dataRouteGetAdvertList"
                        :min-length-search="parseInt(filterMinLengthSearch)"
                        :location-accurate-list="dataFilterLocationAccurateList"
                        :flag-reset-search="dataFlagResetSearch"
                        :search-place-holder="filterSearchPlaceHolder"
                        :location-place-holder="filterLocationPlaceHolder"
                        :route-notifications-exist-in="routeNotificationsExistIn"
                        :route-notifications-add="routeNotificationsAdd"
                        :route-notifications-remove="routeNotificationsRemove"
                        :notifications-checkbox-label="notificationsCheckboxLabel"
                        :route-category="routeCategory"
                        :categories-dropdown-menu-first-menu-name="categoriesDropdownMenuFirstMenuName"
                        :actual-locale="actualLocale"
                        :category-old-choice="parseInt(filter.categoryId)"
                        :categories-all-item="categoriesAllItem"
                    ></advert-filter>
                </div>
                <div class="row">
                    <adverts-by-list
                            :route-get-adverts-list="dataRouteGetAdvertList"
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :ads-frequency="parseInt(adsFrenquency)"
                            :actual-locale="actualLocale"
                            :total-quantity-label="totalQuantityLabel"
                            :lot-mini-quantity-label="lotMiniQuantityLabel"
                            :urgent-label="urgentLabel"
                            :price-info-label="priceInfoLabel"
                            :no-result-found-header="noResultFoundHeader"
                            :no-result-found-message="noResultFoundMessage"
                    ></adverts-by-list>
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
            'bookmarkSuccess',
            'unbookmarkSuccess',
            'modalValidHeader',
            'modalValidDescription',
            'modalNo',
            'modalYes',
            'allLabel',
            //category dropdown menu component
            'routeCategory',
            'categoriesDropdownMenuFirstMenuName',
            'categoriesAllItem',
            'actualLocale',
            //filter advert component
            'filterMinLengthSearch',
            'filterLocationAccurateList',
            'filterRibbonOpen',
            'filterRibbonClose',
            'filterUrgentLabel',
            'filterSearchPlaceHolder',
            'filterLocationPlaceHolder',
            'filterPriceTitle',
            'filterQuantityTitle',
            //advertByList component
            'routeGetAdvertsList',
            'routeBookmarkAdd',
            'routeBookmarkRemove',
            'adsFrenquency',
            'totalQuantityLabel',
            'lotMiniQuantityLabel',
            'urgentLabel',
            'priceInfoLabel',
            'noResultFoundHeader',
            'noResultFoundMessage',
            'routeNotificationsExistIn',
            'routeNotificationsAdd',
            'routeNotificationsRemove',
            'notificationsCheckboxLabel',
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
                dataFilterLocationAccurateList: [],
                dataFilterPricePrefix: '',
                oldMinRangePrice: -1,
                oldMaxRangePrice: -1,
                oldMinRangeQuantity: -1,
                oldMaxRangeQuantity: -1,
                filter: {categoryId: 0},
                paginate: {},
                dataRouteGetAdvertList: '',
                dataFlagResetSearch: false,
                oldChoice: {},
                update: false
            }
        },
        mounted () {
            let that = this;
            if(this.clearStorage){
                sessionStorage.clear();
            }
            this.dataFilterLocationAccurateList = JSON.parse(this.filterLocationAccurateList);
            //Visibility for ADS
//            $('#column1_'+this._uid).children('div').visibility({
//                type   : 'fixed',
//                offset : 112
//            });


            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });

            //Init dataRoute
            if(sessionStorage.getItem('goToCategory') != undefined){
                //En cas de changement de categorie on reinit tout
                this.choiceCategory(sessionStorage.getItem('goToCategory'));
                sessionStorage.removeItem('goToCategory');
            } else {
                //on reconstruit le filtre
                this.initFilterBySessionStorage();
                this.setBreadCrumbItems(this.filter.categoryId);
                this.updateResults();
            }
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
                            this.updateResults();
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
            this.$on('refreshResults', function (query) {
                if(query != undefined && query.length >= this.filterMinLengthSearch){
                    this.filter.resultsFor = query;
                    this.updateResults(true);
                }
            });
            this.$on('clearSearchResults', function () {
                let haveClearAction = this.clearInputSearch();
                if(haveClearAction){
                    this.filter.minPrice=0;
                    this.filter.maxPrice=0;
                    this.filter.minQuantity=0;
                    this.filter.maxQuantity=0;
                    this.updateResults(true);
                }
            });
            this.$on('clearLocationResults', function () {
                this.clearInputLocation();
                this.updateResults();
            });
            this.$on('sendToast', function (event) {
                this.sendToast(event.message, event.type);
            });
            this.$on('bookmarkSuccess', function () {
                this.sendToast(this.bookmarkSuccess, 'success');
            });
            this.$on('unbookmarkSuccess', function () {
                this.sendToast(this.unbookmarkSuccess, 'success');
            });
            this.$on('deleteAdvert', function (event) {
                this.destroyMe(event.url);
            });
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            setBreadCrumbItems: function (categoryId) {
                this.breadcrumbItems = [];
                let that = this;
                if(categoryId != undefined && categoryId>0 ) {
                    axios.get(this.routeCategory+'/'+categoryId)
                        .then(function (response) {
                            let chainedCategories = response.data;
                            that.breadcrumbItems.push({
                                name: that.allLabel,
                                value: 0
                            });
                            chainedCategories.forEach(function (elem,index) {
                                that.breadcrumbItems.push({
                                    name: elem['description'][that.actualLocale],
                                    value: elem.id
                                });
                            });
                        })
                        .catch(function (error) {
                            that.breadcrumbItems.push({
                                name: this.loadErrorMessage,
                                value:''
                            });
                        });
                }
            },
            urlForFilter(priceOnly=false, init=false) {
                let urlBase = init ? this.routeGetAdvertsList : this.dataRouteGetAdvertList;
                let parsed = Parser.parse(urlBase, true);
                parsed.search=undefined;
                parsed.query={};

                //reset sessionStorageFilter
                sessionStorage.removeItem('filter');
                sessionStorage.setItem('filter', JSON.stringify(this.filter));

                for(let elem in this.filter){
                    if(elem != 'minRangePrice' && elem != 'maxRangePrice' && elem != 'maxRangeQuantity' && elem != 'maxRangeQuantity'){
                        parsed.query[elem]=(this.filter[elem]).toString();
                    }
                }
                if(priceOnly){
                    parsed.query.priceOnly=true.toString();
                    if("minPrice" in parsed.query){
                        delete parsed.query.minPrice;
                    }
                    if("maxPrice" in parsed.query){
                        delete parsed.query.maxPrice;
                    }
                    if("minQuantity" in parsed.query){
                        delete parsed.query.minQuantity;
                    }
                    if("maxQuantity" in parsed.query){
                        delete parsed.query.maxQuantity;
                    }
                } else {
                    if("priceOnly" in parsed.query){
                        delete parsed.query.priceOnly;
                    }
                }
                return Parser.format(parsed);
            },
            getMinMaxPrices: function (url, callBack) {
                let that = this;
                axios.get(url)
                    .then(function (response) {
                        that.filter.minRangePrice = parseFloat((response.data).minPrice);
                        that.filter.maxRangePrice = parseFloat((response.data).maxPrice);
                        that.filter.minRangeQuantity = parseInt((response.data).minQuantity);
                        that.filter.maxRangeQuantity = parseInt((response.data).maxQuantity);
                        that.dataFilterPricePrefix = (response.data).currencySymbol;
                        callBack();
                    })
                    .catch(function (error) {
                        this.sendToast(that.loadErrorMessage, 'error');
                    });
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
            clearInputLocation() {
                for(let index in this.dataFilterLocationAccurateList){
                    let key  = this.dataFilterLocationAccurateList[index];
                    if(key in this.filter){
                        delete this.filter[key];
                    }
                }
            },
            choiceCategory(categoryId) {
                this.setBreadCrumbItems(categoryId);
                this.filter.categoryId = parseInt(categoryId);
                this.filter.minPrice = 0;
                this.filter.maxPrice = 0;
                this.filter.minQuantity = 0;
                this.filter.maxQuantity = 0;
                this.clearInputSearch();
                this.updateResults();
            },
            updateResults(){
                let url = this.urlForFilter(true, true);
                //s'assurer que les range min et max sont tjrs valables
                let that = this;
                this.getMinMaxPrices(url, function () {
                    //on test si le filtre min-max est toujours valable
                    if(that.filter.minRangePrice != that.oldMinRangePrice || that.filter.maxRangePrice != that.oldMaxRangePrice || that.filter.minPrice == undefined || that.filter.minPrice < that.filter.minRangePrice || that.filter.minPrice > that.filter.maxRangePrice) {
                        that.filter.minPrice = that.filter.minRangePrice;
                        that.filter.maxPrice = that.filter.maxRangePrice;
                        that.oldMinRangePrice = that.filter.minRangePrice;
                        that.oldMaxRangePrice = that.filter.maxRangePrice;
                    }
                    if(that.filter.minRangePrice != that.oldMinRangePrice || that.filter.maxRangePrice != that.oldMaxRangePrice || that.filter.maxPrice == undefined || that.filter.maxPrice > that.filter.maxRangePrice || that.filter.maxPrice <= that.filter.minPrice) {
                        that.filter.minPrice = that.filter.minRangePrice;
                        that.filter.maxPrice = that.filter.maxRangePrice;
                        that.oldMinRangePrice = that.filter.minRangePrice;
                        that.oldMaxRangePrice = that.filter.maxRangePrice;
                    }
                    //Idem filtre quantity
                    if(that.filter.minRangeQuantity != that.oldMinRangeQuantity || that.filter.maxRangeQuantity != that.oldMaxRangeQuantity || that.filter.minQuantity == undefined || that.filter.minQuantity < that.filter.minRangeQuantity || that.filter.minQuantity > that.filter.maxRangeQuantity) {
                        that.filter.minQuantity = that.filter.minRangeQuantity;
                        that.filter.maxQuantity = that.filter.maxRangeQuantity;
                        that.oldMinRangeQuantity = that.filter.minRangeQuantity;
                        that.oldMaxRangeQuantity = that.filter.maxRangeQuantity;
                    }
                    if(that.filter.minRangeQuantity != that.oldMinRangeQuantity || that.filter.maxRangeQuantity != that.oldMaxRangeQuantity || that.filter.maxQuantity == undefined || that.filter.maxQuantity > that.filter.maxRangeQuantity || that.filter.maxQuantity <= that.filter.minQuantity) {
                        that.filter.minQuantity = that.filter.minRangeQuantity;
                        that.filter.maxQuantity = that.filter.maxRangeQuantity;
                        that.oldMinRangeQuantity = that.filter.minRangeQuantity;
                        that.oldMaxRangeQuantity = that.filter.maxRangeQuantity;
                    }
                    that.update = !that.update;
                    that.dataRouteGetAdvertList = that.urlForFilter(false,true);
                });
            },
            updateFilter(result){
                let oldFilter= _.cloneDeep(this.filter);
                for(let elem in result){
                    if(result[elem] == null){
                        if(elem in this.filter){
                            delete this.filter[elem];
                        }
                    } else {
                        this.filter[elem] = result[elem];
                    }
                }
                if(!_.isEqual(oldFilter, this.filter)){
                    this.updateResults();
                }
            },
            initFilterBySessionStorage: function () {
                if(sessionStorage.getItem('filter') != null){
                    this.filter = JSON.parse(sessionStorage.getItem('filter'));
                }
            },
            destroyMe: function (url) {
                let modalForm = $('#modal2-'+this._uid);
                let that = this;
                modalForm.modal({
                    closable: true,
                    blurring: true,
                    onApprove: function () {
                        axios.delete(url)
                            .then(function (response) {
                                that.updateResults();
                            })
                            .catch(function (error) {
                                if (error.response && error.response.status == 409) {
                                    that.sendToast(error.response.data, 'error');
                                } else {
                                    that.sendToast(that.loadErrorMessage, 'error');
                                }
                                that.isLoaded = false;
                            });
                    }
                }).modal('show');
            }
        }
    }
</script>
