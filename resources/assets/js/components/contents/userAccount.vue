<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <template v-if="advertAccountVerifiedStep">
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
        </template>
        <template v-else>
            <div class="column">
                <h2 class="ui header"><img :src="routeAvatar" alt="" class="ui circular image">{{ dataUserName }}</h2>
            </div>
        </template>
        <div class="column">
            <div class="ui form">
                <div class="field">
                    <div class="three fields">
                        <div class="required field">
                            <label>{{ nameLabel }}</label>
                            <input type="text" name="name" :placeholder="nameLabel" v-model:value="dataUserName"
                                   v-on:keyup.enter="updateByEnter"
                                   v-on:focus="focused={'input': 'name', 'value': dataUserName}"
                                   v-on:blur="blured={'input': 'name', 'value': dataUserName}">
                        </div>
                        <div class="required field">
                            <label>{{ phoneLabel }}</label>
                            <input type="text" name="phone" :placeholder="phoneLabel" v-model:value="dataUserPhone" :maxlength="formPhoneMaxValid"
                                   v-on:keyup.enter="updateByEnter"
                                   v-on:focus="focused={'input': 'phone', 'value': dataUserPhone}"
                                   v-on:blur="blured={'input': 'phone', 'value': dataUserPhone}">
                        </div>
                        <div class="disabled field">
                            <label>{{ emailLabel }}</label>
                            <input type="email" name="email" :placeholder="emailLabel" :value="userEmail">
                        </div>
                    </div>
                </div>
                <template v-if="!advertAccountVerifiedStep">
                    <h4 class="ui horizontal divider header"><i class="options icon"></i> {{ accountPreferencesLabel }} </h4>
                    <div class="field">
                        <div class="two fields">
                            <div class="field">
                                <h5>{{ localesFirstMenuName }}</h5>
                                <locales-dropdown
                                        :route-list-locales="routeListLocales"
                                        :first-menu-name="localesFirstMenuName"
                                        :input-search-label="inputSearchLabel">
                                </locales-dropdown>
                            </div>
                            <div class="field">
                                <h5>{{ currenciesFirstMenuName }}</h5>
                                <currencies-dropdown
                                        :route-list-currencies="routeListCurrencies"
                                        :first-menu-name="currenciesFirstMenuName"
                                        :input-search-label="inputSearchLabel"
                                        :update="currenciesDropDownUpdate">
                                </currencies-dropdown>
                            </div>
                        </div>
                    </div>
                </template>
                <h4 class="ui horizontal divider header">
                    <i class="industry icon"></i>
                    {{ compagnyDivider }}
                </h4>
                <div class="field">
                    <div class="two fields">
                        <div class="required field">
                            <label>{{ compagnyNameLabel }}</label>
                            <input type="text" name="compagny-name" :maxlength="formCompagnyNameMaxValid" :placeholder="compagnyNameLabel" v-model:value="dataCompagnyName"
                                   v-on:keyup.enter="updateByEnter"
                                   v-on:focus="focused={'input': 'compagny-name', 'value': dataCompagnyName}"
                                   v-on:blur="blured={'input': 'compagny-name', 'value': dataCompagnyName}">
                            <transition name="p-fade">
                                <span class="ui red pointing basic label notransition" v-show="dataCompagnyName.length<formCompagnyNameMinValid">{{ formCompagnyNameMinValid }}{{formPointingMinimumChars }}</span>
                            </transition>
                        </div>
                        <div class="required field">
                            <label>{{ compagnyNumberLabel }}</label>
                            <input type="text" name="registration-number" :maxlength="formRegistrationNumberMaxValid" :placeholder="compagnyNumberLabel" v-model:value="dataRegistrationNumber"
                                   v-on:keyup.enter="updateByEnter"
                                   v-on:focus="focused={'input': 'registration-number', 'value': dataRegistrationNumber}"
                                   v-on:blur="blured={'input': 'registration-number', 'value': dataRegistrationNumber}">
                            <transition name="p-fade">
                                <span class="ui red pointing basic label notransition" v-show="dataRegistrationNumber.length<formRegistrationNumberMinValid">{{ formRegistrationNumberMinValid }}{{formPointingMinimumChars }}</span>
                            </transition>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <h4 class="ui horizontal divider header">
                        <i class="map signs icon"></i>
                        {{ googlemapDivider }}
                    </h4>
                    <googleMap
                            :lng="lng"
                            :lat="lat"
                            :geoloc="geoloc"
                            :geoloc-help-msg="geolocHelpMsg"
                            :geoloc-help-msg-two="geolocHelpMsgTwo">
                    </googleMap>
                </div>
                <div class="field" v-if="advertAccountVerifiedStep">
                    <button type="submit" class="ui primary button" v-on:click="submitForm">{{ formValidationButtonLabel }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        directives: {focus: focus},
        props: [
            //vue routes
            'routeUserGetMe',
            'routeUserSetPrefCurrency',
            'routeUserSetPrefLocale',
            'routeUserSetPrefLocation',
            'routeUserSetName',
            'routeUserSetPhone',
            'routeUserSetCompagnyName',
            'routeUserSetRegistrationNumber',
            'routeAvatar',
            'routeNextUrlWithPayment',
            'routeNextUrlWithoutPayment',
            //vue vars
            'userEmail',
            'userName',
            'userPhone',
            'latitude',
            'longitude',
            'firstGeoloc',
            'compagnyName',
            'registrationNumber',
            'advertAccountVerifiedStep',
            'advertCost',
            'advertId',
            'formPhoneMaxValid',
            'formCompagnyNameMinValid',
            'formCompagnyNameMaxValid',
            'formRegistrationNumberMinValid',
            'formRegistrationNumberMaxValid',
            //vue strings
            'loadErrorMessage',
            'accountPatchSuccess',
            'accountPreferencesLabel',
            'nameLabel',
            'emailLabel',
            'phoneLabel',
            'compagnyDivider',
            'compagnyNameLabel',
            'compagnyNumberLabel',
            'contentHeader',
            'geolocHelpMsg',
            'geolocHelpMsgTwo',
            'googlemapDivider',
            'formValidationButtonLabel',
            'formPointingMinimumChars',
            //steps component
            'stepOneTitle',
            'stepTwoTitle',
            'stepThreeTitle',
            'stepOneDescription',
            'stepTwoDescription',
            'stepThreeDescription',
            //locale dropdown component
            'routeListLocales',
            'localesFirstMenuName',
            'inputSearchLabel',
            //currencie dropdown component
            'routeListCurrencies',
            'currenciesFirstMenuName'
        ],
        data: () => {
            return {
                isLoaded: false,
                sendMessage: false,
                typeMessage: '',
                message:'',
                currenciesDropDownUpdate: false,
                lat: '',
                lng: '',
                geoloc: '',
                dataFirstGeoloc: false,
                dataUserName: '',
                dataUserPhone: '',
                dataCompagnyName: '',
                dataRegistrationNumber: '',
                focused: {},
                blured: {},
                steps: []
            };
        },
        mounted () {
            this.steps = [
                {
                    isActive : false,
                    isDisabled : false,
                    isCompleted: true,
                    title: this.stepOneTitle,
                    description: this.stepOneDescription,
                    icon: 'write'
                },
                {
                    isActive : true,
                    isDisabled : false,
                    isCompleted: false,
                    title: this.stepTwoTitle,
                    description: this.stepTwoDescription,
                    icon: 'user'
                },
                {
                    isActive : false,
                    isDisabled : true,
                    isCompleted: false,
                    title: this.stepThreeTitle,
                    description: this.stepThreeDescription,
                    icon: 'payment'
                }
            ];
            this.$on('currencyChoice', function (event) {
                if(event.initial == undefined || event.initial!=true){
                    this.currencyChoice(event.cur);
                }
            });
            this.$on('localeChoice', function (event) {
                if(event.initial == undefined || event.initial!=true){
                    this.localeChoice(event.locale);
                }
            });
            this.$on('locationChange', function (event) {
                this.latLngChange(event);
            });
            this.$on('loadError', function () {
                this.sendToast(this.loadErrorMessage, 'error');
            });
            this.setSteps();
            this.dataUserName = this.userName;
            this.dataUserPhone = this.userPhone;
            this.dataCompagnyName = this.compagnyName;
            this.dataRegistrationNumber = this.registrationNumber;
            sessionStorage.setItem('lat', this.latitude);
            sessionStorage.setItem('lng', this.longitude);
            sessionStorage.setItem('geoloc', this.geoloc);
            let geoCodes = {
                'typeEvent': 'mounted',
                'lat' : this.latitude,
                'lng' : this.longitude,
                'geoloc': this.geoloc
            };
            this.firstGeoloc == '1' ? this.dataFirstGeoloc = true :  null;
            this.$watch('blured', function () {
                if (this.blured.input == this.focused.input && this.blured.value != this.focused.value) {
                    this.updateAccount(this.blured.input, this.blured.value);
                }
            });
        },
        methods: {
            currencyChoice: function (cur) {
                this.$http.patch(this.routeUserSetPrefCurrency, {currency: cur})
                        .then(
                                (response) => {
                                    this.sendToast(this.accountPatchSuccess, 'success');
                                },
                                (response) => {
                                    if(response.status == 409) {
                                        this.sendToast(response.body, 'error');
                                    } else {
                                        this.sendToast(this.loadErrorMessage, 'error');
                                    }
                                }
                        );
            },
            localeChoice: function (locale) {
                this.$http.patch(this.routeUserSetPrefLocale, {localisation: locale})
                        .then(
                                (response) => {
                                        this.sendToast(this.accountPatchSuccess, 'success');
                                        this.currenciesDropDownUpdate = !this.currenciesDropDownUpdate;
                                },
                                (response) => {
                                    if(response.status == 409) {
                                        this.sendToast(response.body, 'error');
                                    } else {
                                        this.sendToast(this.loadErrorMessage, 'error');
                                    }
                                }
                );
            },
            latLngChange: function (event) {
                this.lat= event.lat;
                this.lng= event.lng;
                this.geoloc= event.geoloc;
                if(this.dataFirstGeoloc || parseFloat(this.lat) != parseFloat(this.latitude) || parseFloat(this.lng) != parseFloat(this.longitude)){
                    this.$http.patch(this.routeUserSetPrefLocation, {'lat': this.lat, 'lng': this.lng, 'geoloc': sessionStorage.getItem('geoloc')})
                            .then(
                                    (response) => {
                                        this.dataFirstGeoloc = false;
                                        this.sendToast(this.accountPatchSuccess, 'success');
                                    },
                                    (response) => {
                                        if(response.status == 409) {
                                            this.sendToast(response.body, 'error');
                                        } else {
                                            this.sendToast(this.loadErrorMessage, 'error');
                                        }
                                    }
                            );
                }
            },
            updateAccount: function (inputName, value){
                var updateRoute = '';
                if(inputName == 'name'){
                    updateRoute = this.routeUserSetName;
                } else if(inputName == 'compagny-name') {
                    updateRoute = this.routeUserSetCompagnyName;
                } else if(inputName == 'registration-number') {
                    updateRoute = this.routeUserSetRegistrationNumber;
                } else if(inputName == 'phone') {
                    updateRoute = this.routeUserSetPhone;
                }
                this.$http.patch(updateRoute, {'value': value})
                        .then(
                                function (response) {
                                    this.sendToast(this.accountPatchSuccess, 'success');
                                },
                                function (response) {
                                    if(response.status == 409) {
                                        this.sendToast(response.body, 'error');
                                    } else if(response.status == 422) {
                                        this.sendToast(response.body.value[0], 'error');
                                    } else {
                                        this.sendToast(this.loadErrorMessage, 'error');
                                    }
                                    this.userGetMe();
                                }
                        );
            },
            userGetMe: function () {
                this.$http.get(this.routeUserGetMe)
                    .then(
                        function (response) {
                            this.dataUserName = response.body.userName;
                            this.dataCompagnyName = response.body.compagnyName;
                            this.dataRegistrationNumber = response.body.registrationNumber;
                        },
                        function (response) {
                            this.sendToast(this.loadErrorMessage, 'error');
                        }
                    );
            },
            updateByEnter: function (event) {
                this.updateAccount(event.target.name, event.target.value);
            },
            setSteps () {
                if(parseFloat(this.advertCost)>0) {
                    (this.steps[2]).isDisabled = false;
                    (this.steps[2]).title = this.stepThreeTitle + '(' + (this.advertCost/100).toFixed(2) + '€)';
                } else {
                    (this.steps[2]).isDisabled = true;
                }
            },
            submitForm (event) {
                event.preventDefault();
                if(parseFloat(this.advertCost)>0) {
                    window.location.href=this.routeNextUrlWithPayment+'/'+this.advertId;
                } else {
                    window.location.href=this.routeNextUrlWithoutPayment+'/'+this.advertId;
                }
            },
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            }
        }
    }
</script>
