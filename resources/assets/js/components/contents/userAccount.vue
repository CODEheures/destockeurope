<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <template v-if="advertAccountVerifiedStep">
            <div class="column">
                <h2 class="ui header">{{ contentHeader }}</h2>
            </div>
            <div class="tablet only computer only column">
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
                    <div class="two fields">
                        <div class="field">
                            <label>{{ nameLabel }}</label>
                            <input type="text" name="name" :placeholder="nameLabel" v-model:value="dataUserName"
                                   v-on:keyup.enter="updateByEnter"
                                   v-on:focus="focused={'input': 'name', 'value': dataUserName}"
                                   v-on:blur="blured={'input': 'name', 'value': dataUserName}">
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
                                        :update="currenciesDropDownUpdate"
                                        :input-search-label="inputSearchLabel">
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
            'loadErrorMessage',
            'routeListCurrencies',
            'routeListLocales',
            'localesFirstMenuName',
            'currenciesFirstMenuName',
            'routeUserSetPrefCurrency',
            'routeUserSetPrefLocale',
            'routeUserSetPrefLocation',
            'routeUserSetName',
            'routeUserSetCompagnyName',
            'routeUserSetRegistrationNumber',
            'accountPatchSuccess',
            'routeAvatar',
            'accountPreferencesLabel',
            'userName',
            'userEmail',
            'nameLabel',
            'emailLabel',
            'inputSearchLabel',
            'googlemapDivider',
            'compagnyDivider',
            'compagnyNameLabel',
            'compagnyNumberLabel',
            'geolocHelpMsg',
            'geolocHelpMsgTwo',
            'latitude',
            'longitude',
            'compagnyName',
            'registrationNumber',
            'contentHeader',
            'stepOneTitle',
            'stepTwoTitle',
            'stepThreeTitle',
            'stepOneDescription',
            'stepTwoDescription',
            'stepThreeDescription',
            'advertAccountVerifiedStep',
            'formValidationButtonLabel',
            'advertCost',
            'advertId',
            'formCompagnyNameMinValid',
            'formCompagnyNameMaxValid',
            'formRegistrationNumberMinValid',
            'formRegistrationNumberMaxValid',
            'formPointingMinimumChars',
            'nextUrlWithPayment',
            'nextUrlWithoutPayment'
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
                dataUserName: '',
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
            this.dataCompagnyName = this.compagnyName;
            this.dataRegistrationNumber = this.registrationNumber;
            sessionStorage.setItem('lat', this.latitude);
            sessionStorage.setItem('lng', this.longitude);
            sessionStorage.setItem('geoloc', this.geoloc);
            var geoCodes = {
                'typeEvent': 'mounted',
                'lat' : this.latitude,
                'lng' : this.longitude,
                'geoloc': this.geoloc
            };
            this.latLngChange(geoCodes);
            this.$watch('blured', function () {
                if (this.blured.input == this.focused.input && this.blured.value != this.focused.value) {
                    console.log(this.blured.input);
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
                if(parseFloat(this.lat) != parseFloat(this.latitude) || parseFloat(this.lng) != parseFloat(this.longitude)){
                    this.$http.patch(this.routeUserSetPrefLocation, {'lat': this.lat, 'lng': this.lng, 'geoloc': sessionStorage.getItem('geoloc')})
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
                }
                this.$http.patch(updateRoute, {'value': value})
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
            updateByEnter: function (event) {
                this.updateAccount(event.target.name, event.target.value);
            },
            setSteps () {
                if(parseFloat(this.advertCost)>0) {
                    (this.steps[2]).isDisabled = false;
                } else {
                    (this.steps[2]).isDisabled = true;
                }
            },
            submitForm (event) {
                event.preventDefault();
                if(parseFloat(this.advertCost)>0) {
                    window.location.href=this.nextUrlWithPayment+'/'+this.advertId;
                } else {
                    window.location.href=this.nextUrlWithoutPayment+'/'+this.advertId;
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
