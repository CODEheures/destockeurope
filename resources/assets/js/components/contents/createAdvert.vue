<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="tablet only computer only column">
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
                <input type="hidden" name="main_picture" :value="mainPicture" />
                <div class="ui error message"></div>
                <type-advert-radio-button
                        :route-get-list-type="routeGetListType"
                        :first-menu-name="listTypeFirstMenuName"
                        :old-choice="oldType">
                </type-advert-radio-button>
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
                <div class="required field">
                    <label>{{ advertFormDescriptionLabel }}</label>
                    <textarea name="description" v-model="description"></textarea>
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
                                        <input name="price" type="number" min="0.01" step="0.01" v-model="price" />
                                        <currencies-input-right-label
                                                :route-list-currencies="routeListCurrencies"
                                                :first-menu-name="currenciesFirstMenuName"
                                                :old-currency="oldCurrency">
                                        </currencies-input-right-label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h4 class="ui horizontal divider header">
                    <i class="map signs icon"></i>
                    {{ advertFormGooglemapLabel }}
                </h4>

                <div class="field">
                    <div id="geoloc" class="ui icon info message" :data-lng="geoLocLng" :data-lat="geoLocLat" data-geoloc="" v-on:geochange="latLngChange">
                        <i class="marker icon"></i>
                        <div class="content">
                            <div class="header"></div>
                            <p>{{ geolocHelpMsg }}</p>
                        </div>
                    </div>
                    <div id="map" style="height: 50vh;width: 100%;max-width: 600px;"></div>
                </div>

                <h4 class="ui horizontal divider header">
                    <i class="camera retro icon"></i>
                    {{ advertFormPhotoSeparator }}
                </h4>
                <div class="field" >
                    <div class="ui icon info message">
                        <i class="icon">{{ nbPicturesIndicator }}</i>
                        <div class="content">
                            <div class="header">{{ helpHeaderIndicator }}</div>
                            <p>{{ helpUploadP }}<a :href="helpUploadAHref">{{ helpUploadA }}</a></p>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="ui doubling three column grid">
                        <div class="column" v-for="(thumb,index) in thumbs">
                            <div :class="index>=advertFormPhotoNbFreePicture ? 'ui pink segment' : 'ui segment'">
                                <a class="ui pink right ribbon label" v-if="index>=advertFormPhotoNbFreePicture">{{ advertFormPayPhotoHelpHeaderSingular }}</a>
                                <div class="ui stackable grid">
                                    <div class="four wide centered column">
                                        <a href="#"><i class="large grey remove circle outline icon" :data-file="thumb" v-on:click="delPhoto"></i></a>
                                    </div>
                                    <div class="twelve wide right aligned column">
                                        <div :id="'slider1-'+_uid+'-'+index" class="ui slider checkbox">
                                            <input type="radio" name="mainThumb" :value="thumb">
                                            <label>{{ advertFormMainPhotoLabel }}</label>
                                        </div>
                                    </div>
                                    <div class="sixteen wide column">
                                        <img :src="routeGetTempoThumb+'/'+thumb" class="ui rounded medium centered image" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <template v-if="thumbs.length<maxFiles">
                        <div class="ui grid">
                            <div class="doubling four column row">
                                <div class="column">
                                    <div class="field">
                                        <input type="file" :name="formFileInputName" v-on:change="upload">
                                        <div class="ui top teal pointing basic label">
                                            {{ advertFormPhotoLabel }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="field">
                    <button type="submit" class="ui primary button" v-on:click="submitForm">{{ formValidationButtonLabel }}</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'contentHeader',
            'advertFormTitleLabel',
            'advertFormDescriptionLabel',
            'advertFormPriceLabel',
            'advertFormGooglemapLabel',
            'advertFormPhotoSeparator',
            'advertFormPhotoLabel',
            'advertFormFreePhotoHelpHeaderSingular',
            'advertFormFreePhotoHelpHeaderPlural',
            'advertFormPayPhotoHelpHeaderSingular',
            'advertFormPayPhotoHelpHeaderPlural',
            'advertFormPhotoHelpContent',
            'advertFormPhotoNbFreePicture',
            'advertFormMainPhotoLabel',
            'loadErrorMessage',
            'filesizeErrorMessage',
            'maxFiles',
            'routeCategory',
            'categoryFirstMenuName',
            'routeGetListType',
            'routeAdvertFormPost',
            'routePostTempoPicture',
            'routeGetListTempoThumbs',
            'routeGetTempoThumb',
            'routeDelTempoPicture',
            'listTypeFirstMenuName',
            'formValidationButtonLabel',
            'formTitleMinValid',
            'formTitleMaxValid',
            'formDescriptionMinValid',
            'formDescriptionMaxValid',
            'formPointingMinimumChars',
            'old',
            'routeListCurrencies',
            'currenciesFirstMenuName',
            'actualLocale',
            'geolocHelpMsg',
            'stepOneTitle',
            'stepTwoTitle',
            'stepThreeTitle',
            'stepOneDescription',
            'stepTwoDescription',
            'stepThreeDescription',
        ],
        data: () => {
            return {
                categoryId: '',
                listType: [],
                type: '',
                title: '',
                description: '',
                price: '',
                xCsrfToken: '',
                oldCategoryId: 0,
                oldType: '',
                oldCurrency: '',
                sendMessage: false,
                typeMessage: '',
                message:'',
                currency:'',
                lat: '',
                lng: '',
                geoloc: '',
                helpUploadP: '',
                helpUploadA: '',
                helpUploadAHref: '',
                fileToPost: new FormData(),
                formFileInputName: 'addpicture',
                thumbs: [],
                nbPicturesIndicator: '',
                helpHeaderIndicator: '',
                mainPicture: '',
                steps: [],
                geoLocLng: '',
                geoLocLat:'',
                successFormSubmit: false
            };
        },
        mounted () {
            this.steps = [
                {
                    isActive : true,
                    isDisabled : false,
                    title: this.stepOneTitle,
                    description: this.stepOneDescription,
                    icon: 'write'
                },
                {
                    isActive : false,
                    isDisabled : false,
                    title: this.stepTwoTitle,
                    description: this.stepTwoDescription,
                    icon: 'user'
                },
                {
                    isActive : false,
                    isDisabled : true,
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
                this.currencyChoice(event.cur);
            });
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.xCsrfToken = Laravel.csrfToken;
            this.setPicturesIndicators();
            this.setSteps();
            this.helpUpload();
            this.getListThumbs();
            this.$watch('thumbs', function () {
                this.setPicturesIndicators();
                this.setSteps();
                this.setMainPicture();
            });
            this.getStorage();
        },
        updated () {
            var that = this;
            for(var index in this.thumbs){
                $('#slider1-'+this._uid+'-'+index).checkbox({
                    onChange: function () {
                        that.mainPicture = this.value;
                    }
                });
            }
        },
        methods: {
            typeChoice: function (type) {
                console.log(type);
                this.type = type;
            },
            categoryChoice: function (id) {
                this.categoryId = parseInt(id);
            },
            currencyChoice: function (currency) {
                this.currency = currency;
            },
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            latLngChange: function (event) {
                this.lat= event.target.dataset.lat;
                this.lng= event.target.dataset.lng;
                this.geoloc= event.target.dataset.geoloc;
            },
            helpUpload: function () {
                var htmlObject = $('<p>'+this.advertFormPhotoHelpContent+'</p>');
                this.helpUploadP = htmlObject[0].firstChild.data;
                this.helpUploadA = htmlObject[0].firstElementChild.innerHTML;
                this.helpUploadAHref = htmlObject[0].firstElementChild.href;
            },
            upload: function (event) {
                this.fileToPost.append(this.formFileInputName, event.target.files[0]);
                var that = this;
                this.$http.post(this.routePostTempoPicture, this.fileToPost)
                        .then(
                                function (response) {
                                    that.fileToPost.delete(that.formFileInputName);
                                    that.thumbs = response.body;
                                },
                                function (response) {
                                    that.fileToPost.delete(that.formFileInputName);
                                    if (response.status == 422) {
                                        let msg = response.body.addpicture[0];
                                        that.sendToast(msg, 'error');
                                    } else if(response.status == 413) {
                                        that.sendToast(that.filesizeErrorMessage, 'error');
                                    } else {
                                        that.sendToast(that.loadErrorMessage, 'error');
                                    }
                                }
                        );
            },
            getListThumbs: function (event) {
                var that = this;
                this.$http.get(this.routeGetListTempoThumbs)
                        .then(
                                function (response) {
                                    that.thumbs = response.body;
                                },
                                function (response) {
                                    that.sendToast(that.loadErrorMessage, 'error');
                                }
                        );
            },
            delPhoto: function (event) {
                event.preventDefault();
                var that=this;
                this.$http.delete(this.routeDelTempoPicture+'/'+event.target.dataset.file)
                        .then(
                                function (response) {
                                    that.thumbs = response.body;
                                },
                                function (response) {
                                    that.sendToast(that.loadErrorMessage, 'error');
                                }
                        );
            },
            setPicturesIndicators () {
                var resultIndicator =  this.advertFormPhotoNbFreePicture - this.thumbs.length;
                if(resultIndicator>=0){
                    this.nbPicturesIndicator = resultIndicator;
                    if(resultIndicator>1){
                        this.helpHeaderIndicator = this.advertFormFreePhotoHelpHeaderPlural;
                    } else {
                        this.helpHeaderIndicator = this.advertFormFreePhotoHelpHeaderSingular;
                    }
                } else {
                    this.nbPicturesIndicator = -resultIndicator;
                    if(this.nbPicturesIndicator>1){
                        this.helpHeaderIndicator = this.advertFormPayPhotoHelpHeaderPlural;
                    } else {
                        this.helpHeaderIndicator = this.advertFormPayPhotoHelpHeaderSingular;
                    }
                }
            },
            setSteps () {
                var resultIndicator =  this.thumbs.length - this.advertFormPhotoNbFreePicture;
                if(resultIndicator>=1) {
                    (this.steps[2]).isDisabled = false;
                } else {
                    (this.steps[2]).isDisabled = true;
                }
            },
            setMainPicture() {
                if(this.thumbs.length == 0){
                    this.mainPicture ='';
                } else if(this.thumbs.length == 1 || this.thumbs.indexOf(this.mainPicture)==-1){
                    $('#slider1-'+this._uid+'-0').checkbox('check');
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
                sessionStorage.setItem('description', this.description);
                sessionStorage.setItem('price', this.price);
                sessionStorage.setItem('type', this.type);
                sessionStorage.setItem('currency', this.currency);
                sessionStorage.setItem('lat', this.lat);
                sessionStorage.setItem('lng', this.lng);
            },
            getStorage() {
                sessionStorage.getItem('successFormSubmit') != undefined ? this.successFormSubmit = sessionStorage.getItem('successFormSubmit') : null;
                sessionStorage.getItem('category') != undefined ? this.categoryId = sessionStorage.getItem('category') : null;
                sessionStorage.getItem('category') != undefined? this.oldCategoryId = parseInt(sessionStorage.getItem('category')): null;
                sessionStorage.getItem('title') != undefined ? this.title = sessionStorage.getItem('title') : null;
                sessionStorage.getItem('description') != undefined ? this.description = sessionStorage.getItem('description') : null;
                sessionStorage.getItem('price') != undefined ? this.price = sessionStorage.getItem('price') : null;
                sessionStorage.getItem('type') != undefined ? this.type = sessionStorage.getItem('type') : null;
                sessionStorage.getItem('type') != undefined ? this.oldType = sessionStorage.getItem('type') : null;
                sessionStorage.getItem('currency') != undefined ? this.currency= sessionStorage.getItem('currency') : null;
                sessionStorage.getItem('currency') != undefined ? this.oldCurrency= sessionStorage.getItem('currency') : null;
                sessionStorage.getItem('lat') != undefined ? this.geoLocLat =  sessionStorage.getItem('lat') : null;
                sessionStorage.getItem('lng') != undefined ? this.geoLocLng =  sessionStorage.getItem('lng') : null;
            }
        }
    }
</script>
