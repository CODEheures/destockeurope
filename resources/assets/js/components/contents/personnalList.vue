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
            <div class="sixteen wide tablet twelve wide computer column">
                <div class="row">
                    <adverts-by-list
                            :route-get-adverts-list="dataRouteGetAdvertList"
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :flag-force-reload="flagForceReload"
                            :ads-frequency="parseInt(adsFrequency)"
                            :is-admin-user="isAdminUser==true"
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
                            :form-advert-price-coefficient-total-margin-label="formAdvertPriceCoefficientTotalMarginLabel"
                            :form-advert-price-coefficient-update-label="formAdvertPriceCoefficientUpdateLabel"
                    ></adverts-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                            :pages="paginate"
                            :route-get-list="dataRouteGetAdvertList"
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
            'isAdminUser',
            'isPersonnalList',
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
            'formAdvertPriceCoefficientTotalMarginLabel',
            'formAdvertPriceCoefficientUpdateLabel',
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
                paginate: {},
                dataRouteGetAdvertList: '',
                dataFlagResetSearch: false,
                oldChoice: {},
                update: false,
                flagForceReload: false
            }
        },
        mounted () {
            //Visibility for ADS
//            $('#welcome-ads').children('div').visibility({
//                type   : 'fixed',
//                offset : 112
//            });
            //On load Error
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.$on('updateSuccess', function () {
                this.sendToast(this.updateSuccessMessage, 'success');
            });
            let that = this;

            this.$on('paginate', function (result) {
                this.paginate=result;
            });
            this.$on('changePage', function (url) {
                $('html, body').animate({
                    scrollTop: 0
                }, 600, function () {
                    that.dataRouteGetAdvertList = url;
                });
            });

            this.$on('sendToast', function (event) {
                this.sendToast(event.message, event.type);
            });
            this.dataRouteGetAdvertList = this.routeGetAdvertsList;
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
                        axios.delete(url)
                            .then(function (response) {
                                that.flagForceReload = !that.flagForceReload;
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
