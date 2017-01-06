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
                                <td>{{ advert.price }}</td>
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
                                    <div :class="!advert.user.isDelegation && index>=(advertNbFreePicture*2) ? 'ui pink segment' : 'ui segment'">
                                        <a class="ui pink right ribbon label" v-if="!advert.user.isDelegation && index>=(advertNbFreePicture*2)">{{ advertPayPhotoSingular }}</a>
                                        <div class="ui stackable grid">
                                            <div class="sixteen wide column">
                                                <img :src="routeGetThumb+'/'+picture.hashName+'/'+advert.id" class="ui rounded medium centered image" />
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>

                        <div class="ui grid">
                            <div class="right floated sixteen wide mobile eight wide tablet five wide computer column" v-if="!advert.user.isDelegation">
                                <div class="ui form">
                                    <div class="grouped fields">
                                        <div class="field">
                                            <div :id="'slider1-'+_uid+'-'+advert.id" class="ui slider checkbox">
                                                <input type="radio" :name="advert.id" value="1">
                                                <label>{{ toggleApproveLabel }}</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div :id="'slider2-'+_uid+'-'+advert.id" class="ui slider checkbox">
                                                <input type="radio" :name="advert.id" value="0">
                                                <label>{{ toggleDisapproveLabel }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="right floated sixteen wide mobile sixteen wide tablet ten wide computer column" v-else>
                                <div class="ui form">
                                    <div class="field">
                                        <div class="two fields">
                                            <div class="field">
                                                <label>{{ formAdvertPriceCoefficient }}</label>
                                                <input type="number" name="price_coefficient" min="0" step="1" v-model="advert.priceCoefficient">
                                                <div class="ui pointing label">
                                                    <table class="ui very basic collapsing celled table">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    Nouveau prix affiché
                                                                </td>
                                                                <td>
                                                                    {{ calcMargin(advert,3)  }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    marge/unité
                                                                </td>
                                                                <td>
                                                                    {{ calcMargin(advert,1)  }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    marge totale
                                                                </td>
                                                                <td>
                                                                    {{ calcMargin(advert,2)  }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="grouped fields">
                                                <div class="field">
                                                    <div :id="'slider1-'+_uid+'-'+advert.id" class="ui slider checkbox">
                                                        <input type="radio" :name="advert.id" value="1">
                                                        <label>{{ toggleApproveLabel }}</label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <div :id="'slider2-'+_uid+'-'+advert.id" class="ui slider checkbox">
                                                        <input type="radio" :name="advert.id" value="0">
                                                        <label>{{ toggleDisapproveLabel }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="ui primary button" :class="action ? '': 'disabled'" v-on:click="approveAll">
                        {{ formValidationButtonLabel }}
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
            'formAdvertPriceCoefficient',
            'formAdvertPriceCoefficientExample',
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
                approveList: {},
                sendMessage: false,
                typeMessage: '',
                message: ''
            };
        },
        mounted () {
            this.getAdvertsList();
            console.log('mounte');
        },
        updated () {
            let that = this;
            for(let index in this.advertsList){
                $('#slider1-'+this._uid+'-'+this.advertsList[index]['id']).checkbox({
                    onChange: function () {
                        that.action = true;
                        that.approveList[this.name] = {'isApprove': this.value};
                        console.log(that.approveList);
                    }
                });
                $('#slider2-'+this._uid+'-'+this.advertsList[index]['id']).checkbox({
                    onChange: function () {
                        that.action = true;
                        that.approveList[this.name] = {'isApprove': this.value}
                    }
                });
            }
        },
        methods: {
            getAdvertsList: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                this.approveList={};
                this.advertsList={};
                this.$http.get(this.routeGetAdvertsList)
                        .then(
                                function (response) {
                                    that.advertsList = response.data;
                                    that.isLoaded = true;
                                },
                                function (response)  {
                                    that.sendToast(that.loadErrorMessage, 'error');
                                }
                        );
            },
            approveAll: function (event) {
                event.preventDefault();
                let that = this;
                for(let index in this.approveList){
                    this.approveList[index]['priceCoefficient']= this.advertsList[index]['priceCoefficient'];
                }
                $('#modal-'+this._uid).modal({
                    closable: false,
                    onApprove: function () {
                        that.isLoaded = false;
                        that.$http.post(that.routeAdvertApprove, that.approveList)
                                .then(
                                        function (response) {
                                            that.getAdvertsList();
                                            that.sendToast(that.advertApproveSuccess, 'success');
                                        },
                                        function (response) {
                                            if (response.status == 409) {
                                                that.sendToast(response.body, 'error');
                                            } else {
                                                that.sendToast(that.loadErrorMessage, 'error');
                                            }
                                            that.isLoaded = false;
                                        }
                                );
                    }
                }).modal('show');
            },
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            calcMargin: function (advert, type) {
                let unitMargin =  (((advert.originalPrice*advert.priceCoefficient)/(100*Math.pow(10,advert.priceSubUnit))));
                unitMargin = (Math.floor(unitMargin*Math.pow(10,advert.priceSubUnit))/Math.pow(10,advert.priceSubUnit));
                let totalMargin = unitMargin*advert.totalQuantity;
                if(type == 1) {
                    return (unitMargin.toFixed(advert.priceSubUnit))+advert.currencySymbol
                } else if(type == 2) {
                    return (totalMargin.toFixed(advert.priceSubUnit))+advert.currencySymbol;
                } else if (type == 3) {
                    return (((advert.originalPrice/(Math.pow(10,advert.priceSubUnit))) + unitMargin).toFixed(advert.priceSubUnit))+advert.currencySymbol;
                }
            }
        }
    }
</script>
