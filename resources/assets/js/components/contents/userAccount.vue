<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>

        <div class="column">
            <h2 class="ui header"><img :src="routeAvatar" alt="" class="ui circular image">{{ userName }}</h2>
        </div>
        <div class="column">
            <h4 class="ui horizontal divider header"><i class="options icon"></i> {{ accountPreferencesLabel }} </h4>
            <div class="ui stackable two column grid">
                <div class="column">
                    <h5>{{ localesFirstMenuName }}</h5>
                    <locales-dropdown
                            :route-list-locales="routeListLocales"
                            :first-menu-name="localesFirstMenuName"
                            :input-search-label="inputSearchLabel">
                    </locales-dropdown>
                </div>
                <div class="column">
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
        <div class="column">
            <div class="ui form">
                <h4 class="ui horizontal divider header">
                    <i class="map signs icon"></i>
                    {{ accountGooglemapLabel }}
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
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'loadErrorMessage',
            'routeListCurrencies',
            'routeListLocales',
            'localesFirstMenuName',
            'currenciesFirstMenuName',
            'routeUserSetPrefCurrency',
            'routeUserSetPrefLocale',
            'accountPatchSuccess',
            'routeAvatar',
            'accountPreferencesLabel',
            'userName',
            'inputSearchLabel',
            'accountGooglemapLabel',
            'geolocHelpMsg',
            'geolocHelpMsgTwo'
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
                geoloc: ''
            };
        },
        mounted () {
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
            },
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            }
        }
    }
</script>
