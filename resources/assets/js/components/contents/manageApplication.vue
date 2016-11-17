<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="column">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <div class="ui form">
                <h4 class="ui horizontal divider header"><i class="browser icon"></i> {{ advertPreferencesLabel }} </h4>
                <div class="field">
                    <div class="three fields">
                        <div class="field">
                            <label>{{ advertNbFreePicturesLabel }}</label>
                            <input type="number"
                                   name="nbFreePictures"
                                   min="1"
                                   :max="parameters.nbMaxPictures"
                                   v-model="parameters.nbFreePictures"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'nbFreePictures', 'value': parameters.nbFreePictures}"
                                   v-on:blur="blured={'name': 'nbFreePictures', 'value': parameters.nbFreePictures}">
                        </div>
                        <div class="field">
                            <label>{{ advertNbMaxPicturesLabel }}</label>
                            <input type="number"
                                   name="nbMaxPictures"
                                   min="1"
                                   v-model="parameters.nbMaxPictures"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'nbMaxPictures', 'value': parameters.nbMaxPictures}"
                                   v-on:blur="blured={'name': 'nbMaxPictures', 'value': parameters.nbMaxPictures}">
                        </div>
                        <div class="field">
                            <label>{{ advertUrgentCostLabel }}</label>
                            <input type="number"
                                   name="urgentCost"
                                   min="0"
                                   v-model="parameters.urgentCost"
                                   v-on:keyup.enter="updateParameter"
                                   v-on:focus="focused={'name': 'urgentCost', 'value': parameters.urgentCost}"
                                   v-on:blur="blured={'name': 'urgentCost', 'value': parameters.urgentCost}">
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div class="ui grid">
                        <div class="doubling four column row">
                            <div class="column">
                                <div class="field">
                                    <label>{{ advertPerPageLabel }}</label>
                                    <input type="number"
                                           name="advertsPerPage"
                                           min="4"
                                           v-model="parameters.advertsPerPage"
                                           v-on:keyup.enter="updateParameter"
                                           v-on:focus="focused={'name': 'advertsPerPage', 'value': parameters.advertsPerPage}"
                                           v-on:blur="blured={'name': 'advertsPerPage', 'value': parameters.advertsPerPage}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 class="ui horizontal divider header"><i class="announcement icon"></i> {{ adsPreferencesLabel }} </h4>
                <div class="field">
                    <div class="ui grid">
                        <div class="doubling four column row">
                            <div class="column">
                                <div class="field">
                                    <label>{{ adsFrequencyLabel }}</label>
                                    <input type="number"
                                           name="adsFrequency"
                                           min="0"
                                           :max="parameters.advertPerPage"
                                           v-model="parameters.adsFrequency"
                                           v-on:keyup.enter="updateParameter"
                                           v-on:focus="focused={'name': 'adsFrequency', 'value': parameters.adsFrequency}"
                                           v-on:blur="blured={'name': 'adsFrequency', 'value': parameters.adsFrequency}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <div :id="'slider1-'+_uid" class="ui slider checkbox">
                        <input type="checkbox"
                               name="masterAds"
                               v-model="parameters.masterAds">
                        <label>{{ masterAdsActivationLabel }}</label>
                    </div>
                </div>
                <div class="field">
                    <div class="two fields">
                        <div class="field">
                            <transition name="p-fade">
                                <div class="field" v-show="parameters.masterAds">
                                    <label>{{ masterAdsUrlLabel }}</label>
                                    <input type="url" placeholder="http://ads.google.com/1d5f1d..."
                                           name="urlMasterAds"
                                           v-model="parameters.urlMasterAds"
                                           v-on:keyup.enter="updateParameter"
                                           v-on:focus="focused={'name': 'urlMasterAds', 'value': parameters.urlMasterAds}"
                                           v-on:blur="blured={'name': 'urlMasterAds', 'value': parameters.urlMasterAds}">
                                </div>
                            </transition>
                        </div>
                        <div class="field">
                            <transition name="p-fade">
                                <div class="field" v-show="parameters.masterAds">
                                    <label>{{ masterAdsOffsetYLabel }} (px)</label>
                                    <input type="number"
                                           name="offsetYMasterAds"
                                           v-model="parameters.offsetYMasterAds"
                                           v-on:keyup.enter="updateParameter"
                                           v-on:focus="focused={'name': 'offsetYMasterAds', 'value': parameters.offsetYMasterAds}"
                                           v-on:blur="blured={'name': 'offsetYMasterAds', 'value': parameters.offsetYMasterAds}">
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
                <h4 class="ui horizontal divider header"><i class="paint brush icon"></i> {{ appearanceLabel }} </h4>
                <div class="field">
                    <type-radio-button
                            :route-get-list-type="routeGetListType"
                            :first-menu-name="listTypeFirstMenuName"
                            :old-choice="oldType">
                    </type-radio-button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        directives: {focus: focus},
        props: {
            //vue routes
            routeParameters: String,
            //vue vars
            //vue strings
            contentHeader: String,
            loadErrorMessage: String,
            patchErrorMessage: String,
            patchSuccessMessage: String,
            invalidImageMessage: String,
            advertPreferencesLabel: String,
            advertNbFreePicturesLabel: String,
            advertNbMaxPicturesLabel: String,
            advertUrgentCostLabel: String,
            advertPerPageLabel: String,
            adsPreferencesLabel: String,
            adsFrequencyLabel: String,
            masterAdsActivationLabel: String,
            masterAdsUrlLabel: String,
            masterAdsOffsetYLabel: String,
            appearanceLabel: String,
            welcomeAppearanceLabel: String,
            //type radio btn component
            routeGetListType: String,
            listTypeFirstMenuName: String
        },
        data: () => {
            return {
                isLoaded: false,
                sendMessage: false,
                typeMessage: '',
                message: '',
                focused: {},
                blured: {},
                parameters: [],
                oldType: '',
                isValidImage: false
            };
        },
        mounted () {
            this.getParameters();
            this.$on('typeChoice', function (event) {
                this.typeChoice(event.type);
            });
            var that = this;
            $('#slider1-'+this._uid).checkbox({
               onChange: function () {
                   that.activeMasterAds(that.parameters.masterAds);
               }
            });
            this.$watch('blured', function () {
                if (this.blured.name == this.focused.name && this.blured.value != this.focused.value) {
                    this.updateParameter();
                }
            });
        },
        methods: {
            getParameters: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                this.$http.get(this.routeParameters)
                        .then(
                                function (response) {
                                    that.parameters = response.data;
                                    that.oldType=that.parameters.welcomeType;
                                    that.isLoaded = true;
                                }
                                ,
                                function (response) {
                                    that.sendToast(that.loadErrorMessage, 'error');
                                }
                        )
                ;
            },
            typeChoice: function (type) {
                this.blured.name = 'welcomeType';
                this.blured.value = type;
                this.updateParameter();
            },
            activeMasterAds(flag){
                this.blured.name = 'masterAds';
                this.blured.value = flag;
                this.updateParameter();
            },
            updateParameter: function (event) {
                let patchValue = {};
                let name = '';
                if (event == undefined) {
                    name = this.blured.name;
                    patchValue[name] = this.blured.value;
                } else if ((event instanceof KeyboardEvent) && event.key == "Enter") {
                    name = event.target.name;
                    patchValue[name] = event.target.value;
                    this.focused.value = event.target.value;
                }
                if (name != undefined && patchValue[name] != undefined) {
                    if(name=='urlMasterAds' && patchValue[name] != ''){
                        var that = this;
                        this.testValidImgUrl(patchValue[name], function () {
                            that.updateRequest(patchValue);
                        });
                    } else {
                        this.updateRequest(patchValue);
                    }
                } else {
                    this.getParameters(false);
                    this.sendToast(this.patchErrorMessage, 'error');
                }
            },
            updateRequest(patchValue) {
                let that = this;
                this.$http.patch(this.routeParameters, patchValue)
                        .then(
                                function (response) {
                                    that.getParameters(false);
                                    that.sendToast(that.patchSuccessMessage, 'success');
                                }
                                ,
                                function (response) {
                                    that.getParameters(false);
                                    if (response.status == 409) {
                                        that.sendToast(response.body, 'error');
                                    } else {
                                        that.sendToast(that.patchErrorMessage, 'error');
                                    }
                                }
                        )
                ;
            },
            testValidImgUrl(url, callback){
                var that = this;
                this.$http.get(url)
                        .then(
                                function (response) {
                                    if(response.body && response.body.type){
                                        if(response.body.type.indexOf('image')==0){
                                            that.isValidImage = true;
                                            callback();
                                        }
                                    } else {
                                        that.isValidImage = false;
                                        that.sendToast(this.invalidImageMessage, 'error');
                                    }
                                }
                                ,
                                function (response) {
                                    that.isValidImage = false;
                                    that.sendToast(this.invalidImageMessage, 'error');
                                }
                        )
                ;
            },
            sendToast: function (message, type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            }
        }
    }
</script>
