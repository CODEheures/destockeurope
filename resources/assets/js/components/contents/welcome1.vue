<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div :id="'modal2-'+_uid" class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ strings.modalValidHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="legal icon"></i>
                </div>
                <div class="description">
                    <p>{{ strings.modalValidDescription }}</p>
                </div>
            </div>
            <div class="actions">
                <div class="two fluid ui inverted buttons">
                    <div class="ui cancel red basic inverted button">
                        <i class="remove icon"></i>
                        {{ strings.modalNo }}
                    </div>
                    <div class="ui ok green basic inverted button">
                        <i class="checkmark icon"></i>
                        {{ strings.modalYes }}
                    </div>
                </div>
            </div>
        </div>
        <div class="tablet only computer only sixteen wide column">
            <div class="row">
                <categories-horizontal-menu></categories-horizontal-menu>
            </div>
        </div>
        <div class="sixteen wide column" v-if="masteradsIsActive=='1'">
            <masterads
                    :route-image-server = "masteradsRouteImageServer"
                    :is-active="masteradsIsActive"
                    :url-img="masteradsUrlImg"
                    :url-redirect="masteradsUrlRedirect"
                    :offset-y-main-container="masteradsOffsetYMainContainer"
                    :selector-main-container="'#ads-offset-y-'+_uid"
                    :width="masteradsWidth"
                    :ads-offset-y="-10"
            ></masterads>
        </div>
        <div class="row" :id="'ads-offset-y-'+_uid">
            <div class="sixteen wide column">
                <div class="row filters">
                    <advert-filter
                            :route-notifications-exist-in="routeNotificationsExistIn"
                            :route-notifications-add="routeNotificationsAdd"
                            :route-notifications-remove="routeNotificationsRemove"
                            :breadcrumb-items="breadcrumbItems"
                            :update="update"
                            :filter="filter"
                            :filter-price-prefix="dataFilterPricePrefix"
                            :route-search="dataRouteGetAdvertList"
                            :location-accurate-list="dataFilterLocationAccurateList"
                            :flag-reset-search="dataFlagResetSearch"
                            :category-old-choice="parseInt(filter.categoryId)"
                            :currencies-list="dataFilterCurrenciesList"
                    ></advert-filter>
                </div>
            </div>
            <div class="sixteen wide column">
                <h1 class="ui tiny blue header">{{ dataHeader }}</h1>
            </div>
            <div class="sixteen wide tablet thirteen wide computer column">
                <div class="row">
                    <adverts-by-list
                            :route-get-adverts-list="dataRouteGetAdvertList"
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :ads-frequency="parseInt(adsFrenquency)"
                    ></adverts-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                            :pages="paginate"
                            :route-get-list="dataRouteGetAdvertList"
                            :fake-page-route="getHref()"
                        ></pagination>
                    </div>
                </div>
            </div>
            <div class="computer only three wide column">
                <div class="ui centered grid">
                    <template v-if="dataHighlightAdverts.length > 1">
                        <div class="sixteen wide column" v-for="highLightAdvert in dataHighlightAdverts">
                            <advert-highlight
                                    :advert="highLightAdvert"
                            ></advert-highlight>
                        </div>
                    </template>

                    <template v-else>
                        <div class="sixteen wide column">
                            <advert-highlight v-if="dataHighlightAdverts.length == 1"
                                    :advert="dataHighlightAdverts[0]"
                            ></advert-highlight>
                            <advert-highlight
                                    :advert="dataFakeHighlightAdvert"
                            ></advert-highlight>
                        </div>
                    </template>
                    <div class="sixteen wide column">
                        <vertical-160x600
                            :centered="true"
                            :reload="update"
                        ></vertical-160x600>
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
            'routeGetAdvertsList',
            'routeBookmarkAdd',
            'routeBookmarkRemove',
            'routeNotificationsExistIn',
            'routeNotificationsAdd',
            'routeNotificationsRemove',
            'routeGetHighlight',
            //vue vars
            'clearStorage',
            'forCountryName',
            'forCountryCode',
            'forPage',
            'masteradsRouteImageServer',
            'masteradsIsActive',
            'masteradsUrlImg',
            'masteradsUrlRedirect',
            'masteradsOffsetYMainContainer',
            'masteradsWidth',
            'filterLocationAccurateList',
            'adsFrenquency',
            'fakeHighlightAdvert',
        ],
        data: () => {
            return {
                strings: {},
                properties: {},
                typeMessage : '',
                message : '',
                sendMessage: false,
                breadcrumbItems: [],
                dataFilterLocationAccurateList: [],
                dataFakeHighlightAdvert: {},
                dataFilterCurrenciesList: [],
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
                update: false,
                dataHighlightAdverts: [],
                hash: '',
                isOnHashChange: false,
                dataHeader: '',
                //List of boolean filter params when false=delete of filter
                deleteOnFilterWhenFalse: ['isUrgent', 'isNegociated']
            }
        },
        mounted () {
            this.strings = this.$store.state.strings['welcome1'];
            this.properties = this.$store.state.properties['global'];
            let that = this;
            this.dataFilterLocationAccurateList = JSON.parse(this.filterLocationAccurateList);
            this.dataFakeHighlightAdvert = JSON.parse(this.fakeHighlightAdvert);
            if(this.clearStorage){
                //keep only country
                let inputs = this.initFilterBySessionStorage(true);
                //reset storage
                sessionStorage.clear();
                //recup country
                sessionStorage.setItem('filter', JSON.stringify(this.filter));
                if(inputs.inputLocationVal !== null){
                    sessionStorage.setItem('filterLocationInputVal', inputs.inputLocationVal);
                }
            }

            if(this.forCountryName != "" && this.forCountryCode != "") {
                this.filter['country']=this.forCountryCode;
                sessionStorage.setItem('filterLocationInputVal', JSON.stringify(this.forCountryName.charAt(0).toUpperCase() + this.forCountryName.slice(1)));
                sessionStorage.setItem('filter', JSON.stringify(this.filter));
            }
            if(this.forPage != "") {
                this.filter['page']=this.forPage;
                sessionStorage.setItem('filter', JSON.stringify(this.filter));
            }

            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.strings.loadErrorMessage, 'error');
            });

            //Init dataRoute
            if(sessionStorage.getItem('goToCategory') != undefined){
                //En cas de changement de categorie on reinit tout
                this.initFilterBySessionStorage(true);
                this.choiceCategory(sessionStorage.getItem('goToCategory'));
                sessionStorage.removeItem('goToCategory');
            } else {
                //on reconstruit le filtre
                this.initFilterBySessionStorage();
                this.setBreadCrumbItems(this.filter.categoryId);
                this.updateResults(true);
            }
            this.$on('categoryChoice', function (event) {
                $('html, body').animate({
                    scrollTop: 0
                }, 600);
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
            this.getHighLightAdvert();
            this.$on('paginate', function (result) {
                this.paginate=result;
            });
            this.$on('updateFilter', function (result) {
                this.updateFilter(result);
            });
            this.$on('changePage', function (url) {
                $('html body').animate({
                    scrollTop: 0
                }, 600, function () {
                    let parsed = Parser.parse(url, true);
                    that.filter['page']=parsed.query.page;
                    that.updateResults(true);
                });
            });
            this.$on('refreshResults', function (query) {
                if(query != undefined && query.length >= this.properties.filterMinLengthSearch){
                    this.filter.resultsFor = query;
                    this.updateResults();
                }
            });
            this.$on('clearSearchResults', function () {
                let haveClearAction = this.clearInputSearch();
                if(haveClearAction){
                    this.filter.minPrice=0;
                    this.filter.maxPrice=0;
                    this.filter.minQuantity=0;
                    this.filter.maxQuantity=0;
                    this.updateResults();
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
                this.sendToast(this.strings.bookmarkSuccess, 'success');
            });
            this.$on('unbookmarkSuccess', function () {
                this.sendToast(this.strings.unbookmarkSuccess, 'success');
            });
            if ("onpopstate" in window) {
                this.onPopState();
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
                let that = this;
                if(categoryId != undefined && categoryId>0 ) {
                    axios.get(this.properties.routeCategory+'/'+categoryId)
                        .then(function (response) {
                            let chainedCategories = response.data;
                            that.breadcrumbItems.push({
                                name: that.strings.allLabel,
                                value: 0
                            });
                            chainedCategories.forEach(function (elem,index) {
                                that.breadcrumbItems.push({
                                    name: elem['description'][that.properties.actualLocale],
                                    value: elem.id
                                });
                            });
                            that.setHeader();
                        })
                        .catch(function (error) {
                            that.breadcrumbItems.push({
                                name: this.strings.loadErrorMessage,
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
                    if(this.filter[elem] != null && elem != 'minRangePrice' && elem != 'maxRangePrice' && elem != 'minRangeQuantity' && elem != 'maxRangeQuantity'){
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
                        that.dataFilterCurrenciesList = (response.data).currenciesList;
                        that.filter.currency = (response.data).currency;
                        that.dataFilterPricePrefix = (response.data).currencySymbol;
                        callBack();
                    })
                    .catch(function (error) {
                        that.sendToast(that.strings.loadErrorMessage, 'error');
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
            updateResults(withPage=false){
                if(withPage==false && 'page' in this.filter){
                    delete this.filter.page;
                } else {
                    if('minRangePrice' in this.filter && 'maxRangePrice' in this.filter && 'minRangeQuantity' in this.filter && 'maxRangeQuantity' in this.filter){
                        this.oldMinRangePrice = this.filter.minRangePrice;
                        this.oldMaxRangePrice = this.filter.maxRangePrice;
                        this.oldMinRangeQuantity = this.filter.minRangeQuantity;
                        this.oldMaxRangeQuantity = this.filter.maxRangeQuantity;
                    }
                }
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
                    if(!that.isOnHashChange){
                        that.hashPage();
                        that.setHashFilters();
                    } else {
                        that.isOnHashChange = false;
                    }
                });
            },
            hashPage() {

                let url = document.location.href;
                let parsed = Parser.parse(url, true);
                parsed.search=undefined;
                if("location" in parsed.query){
                    delete parsed.query.location;
                }
                if("page" in parsed.query){
                    delete parsed.query.page;
                }

                if(this.forCountryName != "" && this.forCountryCode != "" && sessionStorage.getItem('filterLocationInputVal')!=undefined){
                    parsed.query['location'] = JSON.parse(sessionStorage.getItem('filterLocationInputVal')).toLowerCase();
                }
                if(this.forPage != "" && 'page' in this.filter){
                    parsed.query['page'] = this.filter.page;
                }
                this.hash = '#' + new Date().getTime().toString(36);
                parsed.hash = this.hash;
                this.setHeader();
                history.pushState({navGuard: true}, '', Parser.format(parsed));
            },
            setHashFilters() {
                if(this.hash !== undefined && this.hash !== ''){
                    sessionStorage.setItem('filter'+this.hash, JSON.stringify(this.filter));
                    let inputLocationVal = sessionStorage.getItem('filterLocationInputVal');
                    if(inputLocationVal !== null){
                        sessionStorage.setItem('filterLocationInputVal'+this.hash, inputLocationVal);
                    }
                }
            },
            onPopState() {
                let that = this;
                window.onpopstate = function (event) {
                    that.isOnHashChange = true;
                    let inputs = that.initFilterBySessionStorage();

                    if(inputs.inputLocationVal !== null){
                        sessionStorage.setItem('filterLocationInputVal', inputs.inputLocationVal);
                    } else {
                        sessionStorage.removeItem('filterLocationInputVal');
                    }
                    that.setBreadCrumbItems(that.filter.categoryId);
                    that.updateResults(true);
                }
            },
            updateFilter(result){
                let oldFilter= _.cloneDeep(this.filter);
                for(let elem in result){
                    if(result[elem] == null || (_.indexOf(this.deleteOnFilterWhenFalse, elem)!==-1 && result[elem]==false)){
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
            initFilterBySessionStorage: function (onlyLocation=false) {
                let hash = window.location.hash;
                let hashFilter = sessionStorage.getItem('filter'+hash);
                let hashInputLocationVal = sessionStorage.getItem('filterLocationInputVal'+hash);

                if(hashFilter !== null){
                    this.filter = JSON.parse(hashFilter);
                    if(onlyLocation===true){
                        for(let elem in this.filter){
                            let isIn = false;
                            for(let index in this.dataFilterLocationAccurateList){
                                let key  = this.dataFilterLocationAccurateList[index];
                                if(elem == key){
                                    isIn = true;
                                }
                            }
                            !isIn ? delete this.filter[elem] : null;
                        }
                    }
                }

                return {'inputLocationVal': hashInputLocationVal};
            },
            getHighLightAdvert: function () {
                let that = this;
                axios.get(this.routeGetHighlight)
                    .then(function (response) {
                        that.dataHighlightAdverts = (response.data).adverts;
                    })
                    .catch(function (error) {
                        //that.sendToast(that.strings.loadErrorMessage, 'error');
                    });
            },
            getHref: function () {
                return window.location.href;
            },
            setHeader: function () {
                this.dataHeader = this.strings.header;
                if(this.breadcrumbItems.length > 0){
                    this.dataHeader = this.dataHeader + ' ' + this.breadcrumbItems[this.breadcrumbItems.length -1].name;
                }
                if(sessionStorage.getItem('filterLocationInputVal')!=undefined){
                    this.dataHeader = this.dataHeader + ' - ' + JSON.parse(sessionStorage.getItem('filterLocationInputVal'));
                }
            }
        }
    }
</script>
