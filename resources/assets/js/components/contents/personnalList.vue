<template>
    <div  class="ui one column grid">
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
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="row">
            <div v-if="isDelegation" class="sixteen wide column">
                <div class="row filters">
                    <advert-simple-search-filter
                            :filter-ribbon-open="filterRibbonOpen"
                            :filter-ribbon-close="filterRibbonClose"
                            :update="update"
                            :filter="filter"
                            :route-search="dataRouteGetAdvertsList"
                            :min-length-search="parseInt(filterMinLengthSearch)"
                            :flag-reset-search="dataFlagResetSearch"
                            :search-place-holder="filterSearchPlaceHolder"
                    ></advert-simple-search-filter>
                </div>
            </div>
            <div :class="isDelegation ? 'sixteen wide column' : 'sixteen wide tablet twelve wide computer column'">
                <div class="row">
                    <div class="ui active inverted dimmer" v-if="!isLoaded">
                        <div class="ui large text loader">Loading</div>
                    </div>
                    <adverts-by-list
                            :route-get-adverts-list="dataRouteGetAdvertsList"
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :flag-force-reload="flagForceReload"
                            :ads-frequency="parseInt(adsFrequency)"
                            :can-get-delegations="canGetDelegations==true"
                            :is-personnal-list="isPersonnalList==true"
                            :actual-locale="actualLocale"
                            :total-quantity-label="totalQuantityLabel"
                            :lot-mini-quantity-label="lotMiniQuantityLabel"
                            :urgent-label="urgentLabel"
                            :is-negociated-label="isNegociatedLabel"
                            :manage-advert-label="manageAdvertLabel"
                            :renew-advert-label="renewAdvertLabel"
                            :back-to-top-label="backToTopAdvertLabel"
                            :highlight-label="highlightAdvertLabel"
                            :delete-advert-label="deleteAdvertLabel"
                            :see-advert-label="seeAdvertLabel"
                            :edit-advert-label="editAdvertLabel"
                            :see-advert-popup-label="seeAdvertPopupLabel"
                            :edit-advert-popup-label="editAdvertPopupLabel"
                            :delete-advert-popup-label="deleteAdvertPopupLabel"
                            :back-to-top-popup-label="backToTopPopupLabel"
                            :highlight-popup-label="highlightPopupLabel"
                            :renew-advert-popup-label="renewAdvertPopupLabel"
                            :validation-on-progress-label="validationOnProgressLabel"
                            :bookmark-info="bookmarkInfo"
                            :views-info="viewsInfo"
                            :price-info-label="priceInfoLabel"
                            :no-result-found-header="noResultFoundHeader"
                            :no-result-found-message="noResultFoundMessage"
                            :reload-on-unbookmark-success="reloadAdvertOnUnbookmarkSuccess==1"
                            :form-advert-price-coefficient-label="formAdvertPriceCoefficientLabel"
                            :form-advert-price-coefficient-new-price-label="formAdvertPriceCoefficientNewPriceLabel"
                            :form-advert-price-coefficient-unit-margin-label="formAdvertPriceCoefficientUnitMarginLabel"
                            :form-advert-price-coefficient-lot-margin-label="formAdvertPriceCoefficientLotMarginLabel"
                            :form-advert-price-coefficient-total-margin-label="formAdvertPriceCoefficientTotalMarginLabel"
                            :form-advert-price-coefficient-update-label="formAdvertPriceCoefficientUpdateLabel"
                    ></adverts-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                            :pages="paginate"
                            :route-get-list="dataRouteGetAdvertsList"
                            :page-label="pageLabel"
                            :page-previous-label="pagePreviousLabel"
                            :page-next-label="pageNextLabel">
                        </pagination>
                    </div>
                </div>
            </div>
            <div v-if="!isDelegation" id="welcome-ads" class="computer only four wide column">
                <div>
                    <div class="sixteen right aligned column">
                        <vertical-160x600></vertical-160x600>
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
            'updateSuccessMessage',
            'contentHeader',
            'modalValidHeader',
            'modalValidDescription',
            'modalNo',
            'modalYes',
            //advertByList component
            'routeGetAdvertsList',
            'routeBookmarkAdd',
            'routeBookmarkRemove',
            'reloadAdvertOnUnbookmarkSuccess',
            'actualLocale',
            'adsFrequency',
            'canGetDelegations',
            'isPersonnalList',
            'isDelegation',
            'totalQuantityLabel',
            'lotMiniQuantityLabel',
            'urgentLabel',
            'isNegociatedLabel',
            'manageAdvertLabel',
            'renewAdvertLabel',
            'backToTopAdvertLabel',
            'highlightAdvertLabel',
            'deleteAdvertLabel',
            'seeAdvertLabel',
            'editAdvertLabel',
            'seeAdvertPopupLabel',
            'editAdvertPopupLabel',
            'deleteAdvertPopupLabel',
            'backToTopPopupLabel',
            'highlightPopupLabel',
            'renewAdvertPopupLabel',
            'validationOnProgressLabel',
            'bookmarkInfo',
            'viewsInfo',
            'priceInfoLabel',
            'noResultFoundHeader',
            'noResultFoundMessage',
            'formAdvertPriceCoefficientLabel',
            'formAdvertPriceCoefficientNewPriceLabel',
            'formAdvertPriceCoefficientUnitMarginLabel',
            'formAdvertPriceCoefficientLotMarginLabel',
            'formAdvertPriceCoefficientTotalMarginLabel',
            'formAdvertPriceCoefficientUpdateLabel',
            //filter advert component
            'filterMinLengthSearch',
            'filterRibbonOpen',
            'filterRibbonClose',
            'filterSearchPlaceHolder',
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
                filter: {},
                paginate: {},
                dataRouteGetAdvertsList: '',
                dataFlagResetSearch: false,
                oldChoice: {},
                update: false,
                flagForceReload: false,
                isLoaded: true,
            }
        },
        mounted () {
            let that = this;
            if(this.clearStorage){
                sessionStorage.clear();
            }
            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.$on('updateSuccess', function () {
                this.sendToast(this.updateSuccessMessage, 'success');
            });

            //on reconstruit le filtre
            this.initFilterBySessionStorage();
            this.updateResults();

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
                    that.dataRouteGetAdvertsList = url;
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
                    this.updateResults(true);
                }
            });
            this.$on('sendToast', function (event) {
                this.sendToast(event.message, event.type);
            });
            this.dataRouteGetAdvertsList = this.routeGetAdvertsList;
            this.$on('deleteAdvert', function (event) {
                this.destroyMe(event.url);
            })
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            destroyMe: function (url) {
                let modalForm = $('#modal2-' + this._uid);
                let that = this;
                modalForm.modal({
                    closable: true,
                    blurring: true,
                    onApprove: function () {
                        that.isLoaded = false;
                        axios.delete(url)
                            .then(function (response) {
                                that.flagForceReload = !that.flagForceReload;
                                that.isLoaded = true;
                            })
                            .catch(function (error) {
                                if (error.response && error.response.status == 409) {
                                    that.sendToast(error.response.data, 'error');
                                } else {
                                    that.sendToast(that.loadErrorMessage, 'error');
                                }
                                that.isLoaded = true;
                            });
                    }
                }).modal('show');
            },
            urlForFilter(init=false) {
                let urlBase = init ? this.routeGetAdvertsList : this.dataRouteGetAdvertsList;
                let parsed = Parser.parse(urlBase, true);
                parsed.search=undefined;
                parsed.query={};

                //reset sessionStorageFilter
                sessionStorage.removeItem('filter');
                sessionStorage.setItem('filter', JSON.stringify(this.filter));

                for(let elem in this.filter){
                    if(this.filter[elem] != null){
                        parsed.query[elem]=(this.filter[elem]).toString();
                    }
                }
                return Parser.format(parsed);
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
            updateResults(){
                this.update = !this.update;
                this.dataRouteGetAdvertsList = this.urlForFilter(true);
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
        }
    }
</script>
