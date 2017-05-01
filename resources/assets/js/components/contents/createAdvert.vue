<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="mobile only tablet only column">
            <steps-light
                    :steps="steps">
            </steps-light>
        </div>
        <div class="computer only column">
            <steps
                    :steps="steps">
            </steps>
        </div>
        <div class="column">
            <form class="ui form" :action="routeAdvertFormPost" method="post">
                <input type="hidden" name="_token" :value="xCsrfToken"/>
                <input type="hidden" name="category" :value="categoryId"/>
                <input type="hidden" name="currency" :value="currency" />
                <input type="hidden" name="lat" :value="lat" />
                <input type="hidden" name="lng" :value="lng" />
                <input type="hidden" name="geoloc" :value="geoloc" />
                <input type="hidden" name="completegeoloc" :value="dataCompleteGeoloc" />
                <input type="hidden" name="searchPlace" :value="searchPlace" />
                <input type="hidden" name="main_picture" :value="mainPicture" />
                <input type="hidden" name="is_urgent" :value="isUrgent ? 1 : 0" />
                <input type="hidden" name="is_negociated" :value="isNegociated ? 1 : 0" />
                <div class="ui error message"></div>
                <div style="display:none">
                    <type-radio-button
                            :route-get-list-type="routeGetListType"
                            :first-menu-name="listTypeFirstMenuName"
                            :old-choice="'bid'"
                            :is-disabled="true"
                    ></type-radio-button>
                </div>
                <!--<type-radio-button-->
                        <!--:route-get-list-type="routeGetListType"-->
                        <!--:first-menu-name="listTypeFirstMenuName"-->
                        <!--:old-choice="isDelegation ? 'bid' : oldType"-->
                        <!--:is-disabled="isDelegation==true"-->
                <!--&gt;</type-radio-button>-->
                <div class="field">
                    <categories-dropdown-menu
                            :route-category="routeCategory"
                            :first-menu-name="categoryFirstMenuName"
                            :actual-locale="actualLocale"
                            :old-choice="oldCategoryId"
                            :with-all="false">
                    </categories-dropdown-menu>
                </div>
                <div class="required field">
                    <label>{{ advertFormTitleLabel }}</label>
                    <input name="title" type="text" :placeholder="advertFormTitleLabel" v-model:value="title" :maxlength="formTitleMaxValid">
                    <transition name="p-fade">
                        <span class="ui red pointing basic label notransition" v-show="title.length<formTitleMinValid">{{ formTitleMinValid }}{{formPointingMinimumChars }}</span>
                    </transition>
                </div>
                <div class="field">
                    <label>{{ advertFormRefLabel }}</label>
                    <input name="manu_ref" type="text" :placeholder="advertFormRefLabel" v-model:value="manuRef">
                </div>
                <div class="required field">
                    <label>{{ advertFormDescriptionLabel }}</label>
                    <textarea name="description" v-model="description" :maxlength="formDescriptionMaxValid"></textarea>
                    <transition name="p-fade">
                        <span class="ui red pointing basic label notransition" v-show="description.length<formDescriptionMinValid">{{ formDescriptionMinValid }}{{formPointingMinimumChars }}</span>
                    </transition>
                </div>

                <div class="field">
                    <div class="ui grid">
                        <div class="doubling four column row">
                            <div class="column">
                                <div class="required field">
                                    <label>{{ advertFormPriceLabel }}</label>
                                    <div class="ui right labeled input">
                                        <template v-if="isNegociated==0">
                                            <input  name="price" type="number" :min="calcSubUnit" :step="calcSubUnit" v-model="price"/>
                                        </template>
                                        <template v-else>
                                            <input  name="" type="number" value="0" disabled/>
                                        </template>
                                        <currencies-input-right-label
                                                :route-list-currencies="routeListCurrencies"
                                                :first-menu-name="currenciesFirstMenuName"
                                                :old-currency="oldCurrency">
                                        </currencies-input-right-label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sixteen wide column" v-if="isDelegation!=1">
                            <div class="field">
                                <div :id="'isNegociated'+_uid" class="ui checkbox">
                                    <input type="checkbox" name="isNegociated">
                                    <label> <span class="ui blue horizontal label">{{ advertExampleIsNegociatedLabel }}</span>{{ advertFormIsNegociatedLabel }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="two fields">
                        <div class="required field">
                            <label>{{ advertFormTotalQuantityLabel }}</label>
                            <input type="number" name="total_quantity" min="1" step="1" v-model="totalQuantity">
                        </div>
                        <div class="required field">
                            <label>{{ advertFormLotMiniQuantityLabel }}</label>
                            <input type="number" name="lot_mini_quantity" min="1" :max="totalQuantity" step="1" v-model="lotMiniQuantity">
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div :id="'isUrgent'+_uid" class="ui checkbox">
                        <input type="checkbox" name="isUrgent">
                        <label> <span class="ui red horizontal label">{{ advertExampleUrgentLabel }}</span>{{ advertFormUrgentLabel }}</label>
                    </div>
                </div>

                <h4 class="ui horizontal divider header">
                    <i class="map signs icon"></i>
                    {{ advertFormGooglemapLabel }}
                </h4>

                <div class="field">
                    <googleMap
                        :lng="lng"
                        :lat="lat"
                        :geoloc="geoloc"
                        :geoloc-help-msg="geolocHelpMsg"
                        :geoloc-help-msg-two="geolocHelpMsgTwo">
                    </googleMap>
                </div>

                <h4 class="ui horizontal divider header">
                    <i class="camera retro icon"></i>
                    {{ advertFormPhotoSeparator }}
                </h4>
                <photo-uploader
                    :route-post-tempo-picture="routePostTempoPicture"
                    :route-get-list-tempo-thumbs="routeGetListTempoThumbs"
                    :route-get-tempo-thumb="routeGetTempoThumb"
                    :route-del-tempo-picture="routeDelTempoPicture"
                    :advert-form-photo-nb-free-picture="parseInt(advertFormPhotoNbFreePicture)"
                    :max-files="parseInt(maxFiles)"
                    :isDelegation="isDelegation==1"
                    :advert-form-photo-btn-label="advertFormPhotoBtnLabel"
                    :advert-form-photo-label="advertFormPhotoLabel"
                    :advert-form-free-photo-help-header-singular="advertFormFreePhotoHelpHeaderSingular"
                    :advert-form-free-photo-help-header-plural="advertFormFreePhotoHelpHeaderPlural"
                    :advert-form-pay-photo-help-header-singular="advertFormPayPhotoHelpHeaderSingular"
                    :advert-form-pay-photo-help-header-plural="advertFormPayPhotoHelpHeaderPlural"
                    :advert-form-photo-help-content="advertFormPhotoHelpContent"
                    :advert-form-main-photo-label="advertFormMainPhotoLabel"
                ></photo-uploader>

                <h4 class="ui horizontal divider header">
                    <i class="film icon"></i>
                    {{ advertFormVideoSeparator }}
                </h4>

                <vimeo-uploader
                    :route-get-video-post-ticket="routeGetVideoPostTicket"
                    :route-del-tempo-video="routeDelTempoVideo"
                    :route-get-status-video="routeGetStatusVideo"
                    :max-video-file-size="parseInt(maxVideoFileSize)"
                    :session-video-id="sessionVideoId"
                    :advert-form-video-btn-label="advertFormVideoSeparator"
                    :advert-form-video-label="advertFormVideoLabel"
                    :advert-form-video-btn-delete="advertFormVideoBtnDelete"
                    :advert-form-video-btn-cancel="advertFormVideoBtnCancel"
                    :waiting-message="waitingMessage"
                    :transcode-message="transcodeMessage"
                ></vimeo-uploader>

                <h4 class="ui horizontal divider header">
                    <i class="share icon"></i>
                    {{ formValidationButtonLabel }}
                </h4>
                <div class="field">
                    <button type="submit" class="ui positive fluid button" v-on:click="submitForm">{{ formValidationButtonLabel }}</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            //vue routes
            'routeAdvertFormPost',
            'routeGetCost',
            //vue vars
            'old',
            'formTitleMinValid',
            'formTitleMaxValid',
            'formDescriptionMinValid',
            'formDescriptionMaxValid',
            'isDelegation',
            //vue strings
            'contentHeader',
            'advertFormTitleLabel',
            'advertFormRefLabel',
            'advertFormDescriptionLabel',
            'advertFormPriceLabel',
            'advertFormGooglemapLabel',
            'advertFormPhotoSeparator',
            'loadErrorMessage',
            'filesizeErrorMessage',
            'formValidationButtonLabel',
            'formPointingMinimumChars',
            'advertFormTotalQuantityLabel',
            'advertFormLotMiniQuantityLabel',
            'advertFormUrgentLabel',
            'advertExampleUrgentLabel',
            'advertFormIsNegociatedLabel',
            'advertExampleIsNegociatedLabel',
            //steps component
            'stepOneTitle',
            'stepTwoTitle',
            'stepThreeTitle',
            'stepThreeTitlePost',
            'stepOneDescription',
            'stepTwoDescription',
            'stepThreeDescription',
            //type-advert component
            'routeGetListType',
            'listTypeFirstMenuName',
            //category component
            'routeCategory',
            'categoryFirstMenuName',
            'actualLocale',
            //currencies-dropdown component
            'routeListCurrencies',
            'currenciesFirstMenuName',
            //geomap component
            'geolocHelpMsg',
            'geolocHelpMsgTwo',
            //Photo component
            'routePostTempoPicture',
            'routeGetListTempoThumbs',
            'routeGetTempoThumb',
            'routeDelTempoPicture',
            'advertFormPhotoNbFreePicture',
            'maxFiles',
            'advertFormPhotoBtnLabel',
            'advertFormPhotoLabel',
            'advertFormFreePhotoHelpHeaderSingular',
            'advertFormFreePhotoHelpHeaderPlural',
            'advertFormPayPhotoHelpHeaderSingular',
            'advertFormPayPhotoHelpHeaderPlural',
            'advertFormPhotoHelpContent',
            'advertFormMainPhotoLabel',
            //vimeo component
            'routeGetVideoPostTicket',
            'routeDelTempoVideo',
            'routeGetStatusVideo',
            'maxVideoFileSize',
            'sessionVideoId',
            'advertFormVideoSeparator',
            'advertFormVideoLabel',
            'advertFormVideoBtnDelete',
            'advertFormVideoBtnCancel',
            'waitingMessage',
            'transcodeMessage',
        ],
        data: () => {
            return {
                categoryId: '',
                listType: [],
                type: '',
                title: '',
                manuRef: '',
                description: '',
                price: '0',
                totalQuantity: 1,
                lotMiniQuantity: 1,
                xCsrfToken: '',
                oldCategoryId: 0,
                oldType: '',
                oldCurrency: '',
                sendMessage: false,
                typeMessage: '',
                message:'',
                currency:'',
                subunit: 2,
                calcSubUnit: 0.01,
                lat: '',
                lng: '',
                geoloc: '',
                dataCompleteGeoloc: '',
                searchPlace: '',
                thumbs: [],
                mainPicture: '',
                steps: [],
                successFormSubmit: false,
                isUrgent: false,
                isNegociated: false,
                hasVideo: false,
                cost: 0,
                onSetSteps: false,
            };
        },
        mounted () {
            this.steps = [
                {
                    isActive : true,
                    isDisabled : false,
                    isCompleted: false,
                    title: this.stepOneTitle,
                    description: this.stepOneDescription,
                    icon: 'write'
                },
                {
                    isActive : false,
                    isDisabled : this.isDelegation == 1,
                    isCompleted: this.isDelegation == 1,
                    title: this.stepTwoTitle,
                    description: this.stepTwoDescription,
                    icon: 'user'
                },
                {
                    isActive : false,
                    isDisabled : true,
                    isCompleted: this.isDelegation == 1,
                    title: this.stepThreeTitle,
                    description: this.stepThreeDescription,
                    icon: 'payment'
                }
            ];
            this.$on('typeChoice', function (event) {
                this.typeChoice(event.type);
            });
            this.$on('categoryChoice', function (event) {
                this.categoryChoice(event.id);
            });
            this.$on('currencyChoice', function (event) {
                this.currencyChoice(event.cur, event.subunit, event.initial);
            });
            this.$on('locationChange', function (event) {
                this.latLngChange(event);
            });
            this.$on('updateThumbs', function (event) {
                this.thumbs = event;
                this.setSteps();
            });
            this.$on('vimeoStateChange', function (hasVideo) {
                this.hasVideo = hasVideo;
                this.setSteps();
            });
            this.$on('updateMainPicture', function (event) {
                this.mainPicture = event;
            });
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.$on('sendToast', function (event) {
                this.sendToast(event.message, event.type);
            });
            this.$on('fileSizeError', function () {
                this.sendToast(this.filesizeErrorMessage, 'error');
            });
            this.xCsrfToken = destockShareVar.csrfToken;
            this.$watch('isUrgent', function () {
                this.setSteps();
                if(this.isUrgent){
                    $('#isUrgent'+this._uid).checkbox('check');
                } else {

                    $('#isUrgent'+this._uid).checkbox('uncheck');
                }
            });
            this.$watch('isNegociated', function () {
                if(this.isNegociated){
                    $('#isNegociated'+this._uid).checkbox('check');
                } else {

                    $('#isNegociated'+this._uid).checkbox('uncheck');
                }
            });
            this.$watch('subunit', function () {
               this.calcSubUnit = Math.pow(10,-(this.subunit));
               this.price = parseFloat(this.price).toFixed(this.subunit);
            });

            this.getStorage();
        },
        updated () {
            let that = this;
            $('#isUrgent'+this._uid).checkbox({
                onChecked: function() {that.isUrgent = true;},
                onUnchecked: function() {that.isUrgent = false;}
            });
            $('#isNegociated'+this._uid).checkbox({
                onChecked: function() {that.isNegociated = true;},
                onUnchecked: function() {that.isNegociated = false;}
            });
        },
        methods: {
            typeChoice: function (type) {
                this.type = type;
            },
            categoryChoice: function (id) {
                this.categoryId = parseInt(id);
            },
            currencyChoice: function (currency, subunit, initial) {
                if(this.oldCurrency == '' || initial==false){
                    this.currency = currency;
                    this.subunit = subunit;
                }
            },
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            latLngChange: function (event) {
                this.lat= event.lat;
                this.lng= event.lng;
                this.geoloc= event.geoloc;
                this.dataCompleteGeoloc = sessionStorage.getItem('geoloc');
                this.searchPlace = sessionStorage.getItem('searchPlace');
            },
            setSteps () {
                let resultIndicator;
                if(this.isDelegation==1){
                    resultIndicator =  this.maxFiles - this.thumbs.length;
                } else {
                    resultIndicator =  this.advertFormPhotoNbFreePicture - this.thumbs.length;
                }
                if(this.isDelegation!=1 && (resultIndicator<0 || this.isUrgent || this.hasVideo)) {
                    if(this.isDelegation != 1) {
                        (this.steps[2]).isDisabled = false;
                    }
                    let that = this;
                    axios.get(this.routeGetCost+'/'+this.thumbs.length + '/'+ this.isUrgent)
                        .then(function (response) {
                            that.cost = response.data;
                            (that.steps[2]).title = that.stepThreeTitle + '(' + (that.cost/100).toFixed(2) + that.stepThreeTitlePost + ')';
                        })
                        .catch(function (error) {
                            that.cost = 0;
                            that.sendToast(that.loadErrorMessage, 'error');
                        });
                } else {
                    this.cost = 0;
                    (this.steps[2]).title = this.stepThreeTitle;
                    (this.steps[2]).isDisabled = true;
                }
            },
            submitForm (event) {
                event.preventDefault();
                this.setStorage();
                event.target.parentNode.parentNode.submit();
            },
            setStorage() {
                sessionStorage.setItem('category', this.categoryId);
                sessionStorage.setItem('title', this.title);
                sessionStorage.setItem('manuRef', this.manuRef);
                sessionStorage.setItem('description', this.description);
                sessionStorage.setItem('price', this.price);
                sessionStorage.setItem('totalQuantity', this.totalQuantity);
                sessionStorage.setItem('lotMiniQuantity', this.lotMiniQuantity);
                sessionStorage.setItem('type', this.type);
                sessionStorage.setItem('currency', this.currency);
                sessionStorage.setItem('subunit', this.subunit);
                sessionStorage.setItem('lat', this.lat);
                sessionStorage.setItem('lng', this.lng);
                sessionStorage.setItem('isUrgent', this.isUrgent);
                sessionStorage.setItem('isNegociated', this.isNegociated);
            },
            getStorage() {
                sessionStorage.getItem('successFormSubmit') != undefined ? this.successFormSubmit = sessionStorage.getItem('successFormSubmit') : null;
                sessionStorage.getItem('category') != undefined ? this.categoryId = sessionStorage.getItem('category') : null;
                sessionStorage.getItem('category') != undefined? this.oldCategoryId = parseInt(sessionStorage.getItem('category')): null;
                sessionStorage.getItem('title') != undefined ? this.title = sessionStorage.getItem('title') : null;
                sessionStorage.getItem('manuRef') != undefined ? this.manuRef = sessionStorage.getItem('manuRef') : null;
                sessionStorage.getItem('description') != undefined ? this.description = sessionStorage.getItem('description') : null;
                sessionStorage.getItem('price') != undefined ? this.price = sessionStorage.getItem('price') : null;
                sessionStorage.getItem('totalQuantity') != undefined ? this.totalQuantity = sessionStorage.getItem('totalQuantity') : null;
                sessionStorage.getItem('lotMiniQuantity') != undefined ? this.lotMiniQuantity = sessionStorage.getItem('lotMiniQuantity') : null;
                sessionStorage.getItem('type') != undefined ? this.type = sessionStorage.getItem('type') : null;
                sessionStorage.getItem('type') != undefined ? this.oldType = sessionStorage.getItem('type') : null;
                sessionStorage.getItem('currency') != undefined ? this.currency= sessionStorage.getItem('currency') : null;
                sessionStorage.getItem('currency') != undefined ? this.oldCurrency= sessionStorage.getItem('currency') : null;
                sessionStorage.getItem('subunit') != undefined ? this.subunit= sessionStorage.getItem('subunit') : null;
                sessionStorage.getItem('subunit') != undefined ? this.oldSubunit= sessionStorage.getItem('subunit') : null;
                sessionStorage.getItem('lat') != undefined ? this.lat =  sessionStorage.getItem('lat') : null;
                sessionStorage.getItem('lng') != undefined ? this.lng =  sessionStorage.getItem('lng') : null;
                sessionStorage.getItem('isUrgent') != undefined ? this.isUrgent =  sessionStorage.getItem('isUrgent') == 'true' : null;
                sessionStorage.getItem('isNegociated') != undefined ? this.isNegociated =  sessionStorage.getItem('isNegociated') == 'true' : null;
            }
        }
    }
</script>
