<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <template v-if="advertAccountVerifiedStep">
            <div class="column">
                <h2 class="ui header">{{ strings.contentHeader }}</h2>
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
                            <label>{{ strings.nameLabel }}</label>
                            <input type="text" name="name" :placeholder="strings.nameLabel" v-model:value="dataUserName"
                                   v-on:keyup.enter="updateByEnter"
                                   v-on:focus="focused={'input': 'name', 'value': dataUserName}"
                                   v-on:blur="blured={'input': 'name', 'value': dataUserName}">
                        </div>
                        <div class="field">
                            <label>{{ strings.phoneLabel }}</label>
                            <input type="text" name="phone" :placeholder="strings.phoneLabel" v-model:value="dataUserPhone" :maxlength="formPhoneMaxValid"
                                   v-on:keyup.enter="updateByEnter"
                                   v-on:focus="focused={'input': 'phone', 'value': dataUserPhone}"
                                   v-on:blur="blured={'input': 'phone', 'value': dataUserPhone}">
                        </div>
                        <div class="field">
                            <div class="sixteen wide disabled field">
                                <label>{{ strings.emailLabel }}</label>
                                <input type="email" name="email" :placeholder="strings.emailLabel" :value="userEmail">
                            </div>
                            <div class="sixteen wide field">
                                <p><a :href="routeChangeEmail">{{ strings.emailChangeLabel }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <a class="ui red button" :href="routeChangePassword">{{ strings.passwordChangeLabel }}</a>
                </div>
                <template v-if="!advertAccountVerifiedStep">
                    <h4 class="ui horizontal divider header"><i class="options icon"></i> {{ strings.accountPreferencesLabel }} </h4>
                    <div class="field">
                        <div class="two fields">
                            <div class="field">
                                <h5>{{ strings.localesFirstMenuName }}</h5>
                                <locales-dropdown-2
                                        :old-locale="''"
                                ></locales-dropdown-2>
                            </div>
                            <div class="field">
                                <h5>{{ strings.currenciesFirstMenuName }}</h5>
                                <currencies-dropdown-2
                                        :old-currency="''">
                                </currencies-dropdown-2>
                            </div>
                        </div>
                    </div>
                </template>
                <h4 class="ui horizontal divider header">
                    <i class="industry icon"></i>
                    {{ strings.compagnyDivider }}
                </h4>
                <div class="field">
                    <div class="two fields">
                        <div class="required field">
                            <label>{{ strings.compagnyNumberLabel }}</label>
                            <div :class="vatOnCheckProgress ? 'ui icon input loading' : 'ui icon input'">
                                <input type="text" name="registration-number" :maxlength="formRegistrationNumberMaxValid" :placeholder="strings.compagnyNumberLabel" v-model:value="dataRegistrationNumber"
                                       v-on:keyup.enter="updateByEnter"
                                       v-on:focus="focused={'input': 'registration-number', 'value': dataRegistrationNumber}"
                                       v-on:blur="blured={'input': 'registration-number', 'value': dataRegistrationNumber}"
                                       :title="!hasValidVat ? strings.formVatWarningLabel:''">
                                <i :class="hasValidVat ? 'green checkmark icon': 'yellow warning sign icon'"></i>
                            </div>
                            <span class="ui orange pointing label" v-show="!hasValidVat && !vatOnCheckProgress">{{ strings.formVatWarningLabel }}</span>
                            <span class="ui green pointing label" v-show="hasValidVat && !vatOnCheckProgress">{{ strings.formVatIdentifierLabel }}{{ dataVatIdentifier }}</span>
                            <span class="ui orange pointing label" v-show="vatOnCheckProgress">{{ strings.formVatOnCheckProgressLabel }}</span>
                        </div>
                        <div class="required field">
                            <label>{{ strings.compagnyNameLabel }}</label>
                            <div :class="vatOnCheckProgress ? 'ui disabled icon input loading' : 'ui input'">
                                <input type="text" name="compagny-name" :maxlength="formCompagnyNameMaxValid" :placeholder="strings.compagnyNameLabel" v-model:value="dataCompagnyName"
                                       v-on:keyup.enter="updateByEnter"
                                       v-on:focus="focused={'input': 'compagny-name', 'value': dataCompagnyName}"
                                       v-on:blur="blured={'input': 'compagny-name', 'value': dataCompagnyName}">
                                <i class="icon"></i>
                            </div>
                            <transition name="p-fade">
                                <span class="ui red pointing basic label notransition" v-show="dataCompagnyName.length<formCompagnyNameMinValid">{{ formCompagnyNameMinValid }}{{strings.formPointingMinimumChars }}</span>
                            </transition>
                        </div>
                    </div>
                </div>
                <div :class="vatOnCheckProgress? 'disabled field':'field'">
                    <h4 class="ui horizontal divider header">
                        <i class="map signs icon"></i>
                        {{ strings.googlemapDivider }}
                    </h4>
                    <googleMap
                            :lng="lng"
                            :lat="lat"
                            :geoloc="geoloc"
                    ></googleMap>
                </div>
                <div class="field" v-if="advertAccountVerifiedStep">
                    <button type="submit" :class="updateFails ? 'ui disabled button' : 'ui primary button'" v-on:click="submitForm">{{ updateFails ? strings.formValidationFailsButtonLabel : strings.formValidationButtonLabel }}</button>
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
            'routeChangeEmail',
            'routeChangePassword',
            'routeUserSetPrefCurrency',
            'routeUserSetPrefLocale',
            'routeUserSetPrefLocation',
            'routeUserSetName',
            'routeUserSetPhone',
            'routeUserSetCompagnyName',
            'routeUserSetRegistrationNumber',
            'routeAvatar',
            'routeNextUrl',
            'routePrices',
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
            'formPhoneMaxValid',
            'formCompagnyNameMinValid',
            'formCompagnyNameMaxValid',
            'formRegistrationNumberMaxValid',
        ],
        data: () => {
            return {
                strings: {},
                properties: {},
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
            this.strings = this.$store.state.strings['user-account'];
            this.properties = this.$store.state.properties['global'];
            this.steps = [
                {
                    isActive : false,
                    isDisabled : false,
                    isCompleted: true,
                    title: this.strings.stepOneTitle,
                    description: this.strings.stepOneDescription,
                    icon: 'write'
                },
                {
                    isActive : true,
                    isDisabled : false,
                    isCompleted: false,
                    title: this.strings.stepTwoTitle,
                    description: this.strings.stepTwoDescription,
                    icon: 'user'
                },
                {
                    isActive : false,
                    isDisabled : true,
                    isCompleted: false,
                    title: this.strings.stepThreeTitle,
                    description: this.strings.stepThreeDescription,
                    routeDescription: this.routePrices,
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
                this.sendToast(this.strings.loadErrorMessage, 'error');
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
                        that.sendToast(that.strings.accountPatchSuccess, 'success');
                    })
                    .catch(function (error) {
                        if(error.response && error.response.status == 409) {
                            that.sendToast(error.response.data, 'error');
                        } else {
                            that.sendToast(that.strings.loadErrorMessage, 'error');
                        }
                    });
            },
            localeChoice: function (locale) {
                let that = this;
                axios.patch(this.routeUserSetPrefLocale, {localisation: locale})
                    .then(function (response) {
                        that.sendToast(that.strings.accountPatchSuccess, 'success');
                        that.currenciesDropDownUpdate = !that.currenciesDropDownUpdate;
                    })
                    .catch(function (error) {
                        if(error.response && error.response.status == 409) {
                            that.sendToast(error.response.data, 'error');
                        } else {
                            that.sendToast(that.strings.loadErrorMessage, 'error');
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
                            that.sendToast(that.strings.accountPatchSuccess, 'success');
                        })
                        .catch(function (error) {
                            if(error.response && error.response.status == 409) {
                                that.sendToast(error.response.data, 'error');
                            } else {
                                that.sendToast(that.strings.loadErrorMessage, 'error');
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
                        that.sendToast(that.strings.accountPatchSuccess, 'success');
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
                            that.sendToast(that.strings.loadErrorMessage, 'error');
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
                        that.sendToast(that.strings.loadErrorMessage, 'error');
                    });
            },
            updateByEnter: function (event) {
                this.focused={'input': event.target.name, 'value': event.target.value};
                this.updateAccount(event.target.name, event.target.value);
            },
            setSteps () {
                if(parseFloat(this.advertCost)>0) {
                    (this.steps[2]).isDisabled = false;
                    (this.steps[2]).title = this.strings.stepThreeTitle + '(' + (this.advertCost/100).toFixed(2) + this.strings.stepThreeTitlePost + ')';
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
