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
                <h2 class="ui header"><img :src="routeAvatar" alt="" class="ui circular image" v-if="routeAvatar && routeAvatar!=''">{{ dataUserName }}</h2>
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
                        <div class="field">
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
                        <div class="field">
                            <label>{{ compagnyNumberLabel }}</label>
                            <div :class="vatOnCheckProgress ? 'ui icon input loading' : 'ui icon input'">
                                <input type="text" name="registration-number" :maxlength="formRegistrationNumberMaxValid" :placeholder="compagnyNumberLabel" v-model:value="dataRegistrationNumber"
                                       v-on:keyup.enter="updateByEnter"
                                       v-on:focus="focused={'input': 'registration-number', 'value': dataRegistrationNumber}"
                                       v-on:blur="blured={'input': 'registration-number', 'value': dataRegistrationNumber}"
                                       :title="!hasValidVat ? formVatWarningLabel:''">
                                <i :class="hasValidVat ? 'green checkmark icon': 'yellow warning sign icon'"></i>
                            </div>
                            <span class="ui orange pointing label" v-show="!hasValidVat && !vatOnCheckProgress">{{ formVatWarningLabel }}</span>
                            <span class="ui green pointing label" v-show="hasValidVat && !vatOnCheckProgress">{{ formVatIdentifierLabel }}{{ dataVatIdentifier }}</span>
                            <span class="ui orange pointing label" v-show="vatOnCheckProgress">{{ formVatOnCheckProgressLabel }}</span>
                        </div>
                        <div class="required field">
                            <label>{{ compagnyNameLabel }}</label>
                            <div :class="vatOnCheckProgress ? 'ui disabled icon input loading' : 'ui input'">
                                <input type="text" name="compagny-name" :maxlength="formCompagnyNameMaxValid" :placeholder="compagnyNameLabel" v-model:value="dataCompagnyName"
                                       v-on:keyup.enter="updateByEnter"
                                       v-on:focus="focused={'input': 'compagny-name', 'value': dataCompagnyName}"
                                       v-on:blur="blured={'input': 'compagny-name', 'value': dataCompagnyName}">
                                <i class="icon"></i>
                            </div>
                            <transition name="p-fade">
                                <span class="ui red pointing basic label notransition" v-show="dataCompagnyName.length<formCompagnyNameMinValid">{{ formCompagnyNameMinValid }}{{formPointingMinimumChars }}</span>
                            </transition>
                        </div>
                    </div>
                </div>
                <div :class="vatOnCheckProgress? 'disabled field':'field'">
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
                    <button type="submit" :class="updateFails ? 'ui disabled button' : 'ui primary button'" v-on:click="submitForm">{{ updateFails ? formValidationFailsButtonLabel : formValidationButtonLabel }}</button>
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
            'routeNextUrl',
            //vue vars
            'userEmail',
            'userName',
            'userPhone',
            'latitude',
            'longitude',
            'firstGeoloc',
            'compagnyName',
            'registrationNumber',
            'vatIdentifier',
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
            'formValidationFailsButtonLabel',
            'formPointingMinimumChars',
            'formVatWarningLabel',
            'formVatOnCheckProgressLabel',
            'formVatIdentifierLabel',
            //steps component
            'stepOneTitle',
            'stepTwoTitle',
            'stepThreeTitle',
            'stepThreeTitlePost',
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
                dataVatIdentifier: '',
                focused: {},
                blured: {},
                steps: [],
                updateInProgress: 0,
                updateFails: false,
                vatOnCheckProgress: false,
                hasValidVat: false,
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
            this.dataVatIdentifier = this.vatIdentifier;
            this.hasValidVat = this.vatIdentifier != '';
            sessionStorage.setItem('lat', this.latitude);
            sessionStorage.setItem('lng', this.longitude);
            sessionStorage.setItem('geoloc', this.geoloc);
            this.firstGeoloc == '1' ? this.dataFirstGeoloc = true :  null;
            this.$watch('blured', function () {
                if (this.blured.input == this.focused.input && this.blured.value != this.focused.value) {
                    this.updateAccount(this.blured.input, this.blured.value);
                }
            });
        },
        methods: {
            currencyChoice: function (cur) {
                let that = this;
                axios.patch(this.routeUserSetPrefCurrency, {currency: cur})
                    .then(function (response) {
                        that.sendToast(that.accountPatchSuccess, 'success');
                    })
                    .catch(function (error) {
                        if(error.response && error.response.status == 409) {
                            that.sendToast(error.response.data, 'error');
                        } else {
                            that.sendToast(that.loadErrorMessage, 'error');
                        }
                    });
            },
            localeChoice: function (locale) {
                let that = this;
                axios.patch(this.routeUserSetPrefLocale, {localisation: locale})
                    .then(function (response) {
                        that.sendToast(that.accountPatchSuccess, 'success');
                        that.currenciesDropDownUpdate = !that.currenciesDropDownUpdate;
                    })
                    .catch(function (error) {
                        if(error.response && error.response.status == 409) {
                            that.sendToast(error.response.data, 'error');
                        } else {
                            that.sendToast(that.loadErrorMessage, 'error');
                        }
                    });
            },
            latLngChange: function (event) {
                let that = this;
                this.lat= event.lat;
                this.lng= event.lng;
                this.geoloc= event.geoloc;
                if(this.dataFirstGeoloc || parseFloat(this.lat) != parseFloat(this.latitude) || parseFloat(this.lng) != parseFloat(this.longitude)){
                    axios.patch(this.routeUserSetPrefLocation, {'lat': this.lat, 'lng': this.lng, 'geoloc': sessionStorage.getItem('geoloc')})
                        .then(function (response) {
                            that.dataFirstGeoloc = false;
                            that.sendToast(that.accountPatchSuccess, 'success');
                        })
                        .catch(function (error) {
                            if(error.response && error.response.status == 409) {
                                that.sendToast(error.response.data, 'error');
                            } else {
                                that.sendToast(that.loadErrorMessage, 'error');
                            }
                        });
                }
            },
            updateAccount: function (inputName, value){
                this.updateInProgress++;
                let that = this;
                let updateRoute = '';
                if(inputName == 'name'){
                    updateRoute = this.routeUserSetName;
                } else if(inputName == 'compagny-name') {
                    updateRoute = this.routeUserSetCompagnyName;
                } else if(inputName == 'registration-number') {
                    updateRoute = this.routeUserSetRegistrationNumber;
                    this.vatOnCheckProgress=true;
                } else if(inputName == 'phone') {
                    updateRoute = this.routeUserSetPhone;
                }
                axios.patch(updateRoute, {'value': value})
                    .then(function (response) {
                        that.updateFails = false;
                        that.sendToast(that.accountPatchSuccess, 'success');
                        that.updateInProgress--;
                        if(inputName == 'registration-number'){
                            that.userGetMe();
                        }
                    })
                    .catch(function (error) {
                        that.updateFails = true;
                        that.updateInProgress--;
                        that.vatOnCheckProgress=false;
                        if(error.response && error.response.status == 409) {
                            that.sendToast(error.response.data, 'error.response');
                        } else if(error.response && error.response.status == 422) {
                            that.sendToast(error.response.data.value[0], 'error');
                        } else {
                            that.sendToast(that.loadErrorMessage, 'error');
                        }
                        that.userGetMe();
                    });
            },
            userGetMe: function () {
                let that = this;
                axios.get(this.routeUserGetMe)
                    .then(function (response) {
                        that.dataUserName = response.data.userName;
                        that.dataCompagnyName = response.data.compagnyName;
                        that.dataRegistrationNumber = response.data.registrationNumber;
                        that.dataVatIdentifier= response.data.vatIdentifier;
                        that.hasValidVat = that.dataVatIdentifier != null && that.dataVatIdentifier != '';
                        that.lng= response.data.lng;
                        that.lat= response.data.lat;
                        that.geoloc= response.data.geoloc;
                        sessionStorage.setItem('lat', that.lat);
                        sessionStorage.setItem('lng', that.lng);
                        sessionStorage.setItem('geoloc', that.geoloc);
                        window.destockMap.initLocation();
                        that.vatOnCheckProgress=false;
                    })
                    .catch(function (error) {
                        that.vatOnCheckProgress=false;
                        that.sendToast(that.loadErrorMessage, 'error');
                    });
            },
            updateByEnter: function (event) {
                this.focused={'input': event.target.name, 'value': event.target.value};
                this.updateAccount(event.target.name, event.target.value);
            },
            setSteps () {
                if(parseFloat(this.advertCost)>0) {
                    (this.steps[2]).isDisabled = false;
                    (this.steps[2]).title = this.stepThreeTitle + '(' + (this.advertCost/100).toFixed(2) + this.stepThreeTitlePost + ')';
                } else {
                    (this.steps[2]).isDisabled = true;
                }
            },
            submitForm (event) {
                event.preventDefault();
                this.isLoaded = false;
                let counter = 0;
                let that = this;
                let timer = function () {
                    setTimeout(function () {
                        if(that.updateInProgress <= 0){
                            window.location.assign(that.routeNextUrl);
                        } else {
                            counter++;
                            if (counter < 20) {
                                timer();
                            } else {
                                that.isLoaded = true;
                                that.updateFails = true;
                            }
                        }
                    }, 250);
                };
                timer();
            },
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            }
        }
    }
</script>
