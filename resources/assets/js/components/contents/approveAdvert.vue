<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div :id="'modal-'+_uid" class="ui basic modal">
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
        <div class="column">
            <div class="ui divided list">
                <div class="ui active inverted dimmer" v-if="!isLoaded">
                    <div class="ui large text loader">Loading</div>
                </div>
                <form>
                    <div v-for="(advert,index) in advertsList" class="ui segment">
                        <table class="ui definition table">
                            <tbody>
                            <tr>
                                <td class="three wide column">{{ advertTitleLabel }}</td>
                                <td>{{ advert.title }}</td>
                            </tr>
                            <tr>
                                <td class="three wide column">{{ advertDescriptionLabel }}</td>
                                <td><p style="white-space: pre-wrap;">{{ advert.description }}</p></td>
                            </tr>
                            <tr>
                                <td class="three wide column">{{ advertPriceLabel }}</td>
                                <td>{{ advert.price }} {{ advert.currency}}</td>
                            </tr>
                            <tr>
                                <td class="three wide column">{{ totalQuantityLabel }} / {{ lotMiniQuantityLabel }}</td>
                                <td>{{ advert.totalQuantity }} / {{ advert.lotMiniQuantity}}</td>
                            </tr>
                            <tr>
                                <td class="three wide column">{{ advertAddressLabel }}</td>
                                <td>{{ advert.geoloc }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="ui doubling three column grid">
                            <div class="column" v-for="(picture,index) in advert.pictures" v-if="picture.isThumb">
                                    <div :class="index>=(advertNbFreePicture*2) ? 'ui pink segment' : 'ui segment'">
                                        <a class="ui pink right ribbon label" v-if="index>=(advertNbFreePicture*2)">{{ advertPayPhotoSingular }}</a>
                                        <div class="ui stackable grid">
                                            <div class="sixteen wide column">
                                                <img :src="routeGetThumb+'/'+picture.hashName+'/'+advert.id" class="ui rounded medium centered image" />
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <div class="ui grid">
                            <div class="right floated sixteen wide mobile eight wide tablet five wide computer column">
                                <div class="ui form">
                                    <div class="grouped fields">
                                        <div class="field">
                                            <div :id="'slider1-'+_uid+'-'+index" class="ui slider checkbox">
                                                <input type="radio" :name="advert.id" value="1">
                                                <label>{{ toggleApproveLabel }}</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div :id="'slider2-'+_uid+'-'+index" class="ui slider checkbox">
                                                <input type="radio" :name="advert.id" value="0">
                                                <label>{{ toggleDisapproveLabel }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="ui primary button" :class="action ? '': 'disabled'" v-on:click="approveAll">{{
                        formValidationButtonLabel }}
                    </button>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            //vue routes
            'routeGetAdvertsList',
            'routeAdvertApprove',
            'routeGetThumb',
            //vue vars
            'advertNbFreePicture',
            //vue strings
            'contentHeader',
            'loadErrorMessage',
            'toggleApproveLabel',
            'toggleDisapproveLabel',
            'formValidationButtonLabel',
            'modalValidHeader',
            'modalValidDescription',
            'modalNo',
            'modalYes',
            'advertPayPhotoSingular',
            'advertTitleLabel',
            'advertDescriptionLabel',
            'advertPriceLabel',
            'advertAddressLabel',
            'advertApproveSuccess',
            'totalQuantityLabel',
            'lotMiniQuantityLabel'
        ],
        data: () => {
            return {
                advertsList: [],
                isLoaded: false,
                action: false,
                approveList: [],
                sendMessage: false,
                typeMessage: '',
                message: ''
            };
        },
        mounted () {
            this.getAdvertsList();
        },
        updated () {
            var that = this;
            for(var index in this.advertsList){
                $('#slider1-'+this._uid+'-'+index).checkbox({
                    onChange: function () {
                        that.action = true;
                        that.approveList[this.name] = this.value;
                    }
                });
                $('#slider2-'+this._uid+'-'+index).checkbox({
                    onChange: function () {
                        that.action = true;
                        that.approveList[this.name] = this.value;
                    }
                });
            }
        },
        methods: {
            getAdvertsList: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                this.$http.get(this.routeGetAdvertsList)
                        .then(
                                function (response) {
                                    this.advertsList = response.data;
                                    this.isLoaded = true;
                                },
                                function (response)  {
                                    this.sendToast(this.loadErrorMessage, 'error');
                                }
                        );
            },
            approveAll: function (event) {
                event.preventDefault();
                var that = this;
                $('#modal-'+this._uid).modal({
                    closable: false,
                    onApprove: function () {
                        that.isLoaded = false;
                        that.$http.post(that.routeAdvertApprove, that.approveList)
                                .then(
                                        (response) => {
                                            that.getAdvertsList();
                                            that.sendToast(that.advertApproveSuccess, 'success');
                                        },
                                        (response) => {
                                            if (response.status == 409) {
                                                that.sendToast(response.body, 'error');
                                            }
                                            that.isLoaded = false;
                                            that.sendToast(that.loadErrorMessage, 'error');
                                        }
                                );
                    }
                }).modal('show');
            },
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            }
        }
    }
</script>
