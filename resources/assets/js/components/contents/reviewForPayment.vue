<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
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
        <div class="column">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <template>
                <table class="ui compact unstackable table">
                    <thead>
                        <tr>
                            <th>{{ strings.tableHeaderOptionName }}</th>
                            <th>{{ strings.tableHeaderOptionQuantity }}</th>
                            <th>{{ strings.tableHeaderOptionCost }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="option in dataInvoice.options">
                            <td>
                                <h5 class="ui center aligned header">{{ option.name }}</h5>
                            </td>
                            <td class="single line">
                                {{ option.quantity }}
                            </td>
                            <td class="single line">
                                {{ (option.cost/100).toFixed(2) }}€
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="ui grid">
                    <div class="right aligned column">
                        <table style="float: right" class="ui very basic collapsing celled compact unstackable table">
                            <tbody>
                                <tr>
                                    <td class="four wide double">{{ strings.tableTotalExclVat }}</td>
                                    <td class="four wide double right aligned">{{ (dataInvoice.cost/100).toFixed(2) }}€</td>
                                </tr>
                                <tr>
                                    <td class="four wide double">{{ strings.tableTotalVat }}</td>
                                    <td class="four wide double right aligned">{{ (tva/100).toFixed(2) }}€</td>
                                </tr>
                                <tr>
                                    <td class="four wide double">{{ strings.tableTotalInclVat }}</td>
                                    <td class="four wide double right aligned">{{ ((dataInvoice.cost + tva)/100).toFixed(2) }}€</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="ui grid">
                    <div class="column">
                        <div class="ui compact right floated segment">
                            <div id="cgvSlider" class="ui toggle checkbox">
                                <input type="checkbox" name="cgv">
                                <label>{{ dataCgvText }} <a :href="dataCgvHref" target="_blank">{{ dataCgvA }}</a></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ui icon attached info message">
                    <i class="lock icon"></i>
                    <div class="content">
                        <div class="header">{{ strings.lockInfoHeader }}</div>
                        <p>{{ strings.lockInfoContent }}</p>
                    </div>
                </div>
                <div :class="isCgvApprove ? 'ui attached fluid segment':'ui disabled attached fluid segment'">
                    <div class="ui grid">
                        <div class="sixteen wide column">
                            <div class="ui center aligned basic segment">
                                {{ strings.infoPaymentType }} <i :id="'help-'+_uid" class="circular help icon link"></i>
                                <div class="ui flowing special popup">
                                    <img class="ui huge centered image" :src="urlImgPaypalInfo" />
                                </div>
                            </div>
                            <a :href="dataRoutePaypalChoice" data-paypal-button="true" :title="strings.paypalBtnTitle">
                                <img class="ui medium centered image spaced-top-2" :src="dataUrlImgPaypal" :alt="strings.paypalBtnTitle" />
                            </a>
                            <!--<div class="spaced-top-2">-->
                                <!--<div class="ui horizontal divider">-->
                                    <!--{{ strings.dividerChoiceLabel }}-->
                                <!--</div>-->
                            <!--</div>-->
                        </div>
                        <!--<div class="sixteen wide mobile eight wide tablet six wide computer centered column">-->
                            <!--<form accept-charset="UTF-8" autocomplete="off" :action="routeCardChoice"  id="payment-form" method="post" class="ui form" >-->
                                <!--<input type="hidden" name="_token" :value="properties.csrfToken"/>-->
                                <!--<div :class="isCgvApprove ? 'field' : 'disabled field'">-->
                                    <!--<select class="ui fluid search dropdown" name="card_type">-->
                                        <!--<option value="">{{ strings.paymentCardTypeLabel }}</option>-->
                                        <!--<option :value="index" v-for="(card, index) in dataCardsTypes">{{ card }}</option>-->
                                    <!--</select>-->
                                <!--</div>-->
                                <!--<div :class="isCgvApprove ? 'field' : 'disabled field'">-->
                                    <!--<label>{{ strings.paymentCardNameLabel }}</label>-->
                                    <!--<input type='text' name="name">-->
                                <!--</div>-->
                                <!--<div class="two fields">-->
                                    <!--<div :class="isCgvApprove ? 'twelve wide field' : 'twelve wide disabled field'">-->
                                        <!--<label>{{ strings.paymentCardNumberLabel }}</label>-->
                                        <!--<input type="text" name="card_no" maxlength="25" :placeholder="strings.paymentCardNumberPlaceholder">-->
                                    <!--</div>-->
                                    <!--<div :class="isCgvApprove ? 'four wide field' : 'four wide disabled field'">-->
                                        <!--<label>{{ strings.paymentCardCvcLabel }}</label>-->
                                        <!--<input type="text" name="cvc" maxlength="3" :placeholder="strings.paymentCardCvcLabel">-->
                                    <!--</div>-->
                                <!--</div>-->
                                <!--<div class="field">-->
                                    <!--<label>{{ strings.paymentCardExpirationLabel }}</label>-->
                                    <!--<div class="two fields">-->
                                        <!--<div :class="isCgvApprove ? 'field' : 'disabled field'">-->
                                            <!--<select class="ui fluid search dropdown" name="expiration_month">-->
                                                <!--<option value="">{{ strings.paymentCardExpirationMonthPlaceholder }}</option>-->
                                                <!--<option value="1">{{ strings.january }}</option>-->
                                                <!--<option value="2">{{ strings.february }}</option>-->
                                                <!--<option value="3">{{ strings.march }}</option>-->
                                                <!--<option value="4">{{ strings.april }}</option>-->
                                                <!--<option value="5">{{ strings.may }}</option>-->
                                                <!--<option value="6">{{ strings.june }}</option>-->
                                                <!--<option value="7">{{ strings.july }}</option>-->
                                                <!--<option value="8">{{ strings.august }}</option>-->
                                                <!--<option value="9">{{ strings.september }}</option>-->
                                                <!--<option value="10">{{ strings.october }}</option>-->
                                                <!--<option value="11">{{ strings.november }}</option>-->
                                                <!--<option value="12">{{ strings.december }}</option>-->
                                            <!--</select>-->
                                        <!--</div>-->
                                        <!--<div :class="isCgvApprove ? 'field' : 'disabled field'">-->
                                            <!--<input type="text" name="expiration_year" maxlength="4" :placeholder="strings.paymentCardExpirationYearPlaceholder">-->
                                        <!--</div>-->
                                    <!--</div>-->
                                <!--</div>-->
                                <!--<button type="submit" :class="isCgvApprove ? 'ui primary right labeled icon button':'ui disabled primary right labeled icon button'">-->
                                    <!--<i class="right arrow icon"></i>Payer</button>-->
                            <!--</form>-->
                        <!--</div>-->
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            //vue routes
            'routePaypalChoice',
            'routeCardChoice',
            'routePrices',
            //vue vars
            'invoice',
            'cardsTypes',
            'urlImgPaypalDisabled',
            'urlImgPaypalEnabled',
            'urlImgPaypalInfo'
        ],
        data: () => {
            return {
                strings: {},
                properties: {},
                isLoaded: true,
                sendMessage: false,
                typeMessage: '',
                message: '',
                steps: [],
                tva: 0,
                dataCgvText: '',
                dataCgvA: '',
                dataCgvHref: '',
                isCgvApprove: false,
                dataRoutePaypalChoice: '',
                dataUrlImgPaypal: null,
                dataInvoice: {},
                dataCardsTypes: []
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['review-for-payment'];
            this.properties = this.$store.state.properties['global'];
            this.dataInvoice = JSON.parse(this.invoice);
            this.dataCardsTypes = JSON.parse(this.cardsTypes);
            this.dataUrlImgPaypal = this.urlImgPaypalDisabled;
            this.dataRoutePaypalChoice = null;
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
                    isActive : false,
                    isDisabled : false,
                    isCompleted: true,
                    title: this.strings.stepTwoTitle,
                    description: this.strings.stepTwoDescription,
                    icon: 'user'
                },
                {
                    isActive : true,
                    isDisabled : false,
                    isCompleted: false,
                    title: this.strings.stepThreeTitle,
                    description: this.strings.stepThreeDescription,
                    routeDescription: this.routePrices,
                    icon: 'payment'
                }
            ];
            this.setSteps();
            this.calcTVA();
            this.setDataCgv();
        },
        updated () {
            let that = this;
            $('#cgvSlider').checkbox({
                onChecked: function() {
                    that.isCgvApprove = true;
                    that.dataUrlImgPaypal = that.urlImgPaypalEnabled;
                    that.dataRoutePaypalChoice = that.routePaypalChoice;
                },
                onUnchecked: function() {
                    that.isCgvApprove = false;
                    that.dataUrlImgPaypal = that.urlImgPaypalDisabled;
                    that.dataRoutePaypalChoice = null;
                }
            });
            $('#payment-form')
                .form({
                    on: 'blur',
                    inline: true,
                    fields: {
                        card_no: {
                            identifier  : 'card_no',
                            rules: [
                                {
                                    type   : 'creditCard',
                                    prompt : that.strings.paymentCardNumberError
                                }
                            ]
                        },
                        name: {
                            identifier  : 'name',
                            rules: [
                                {
                                    type   : 'regExp[/^[A-Za-z\\s]{1,255}$/]',
                                    prompt : that.strings.paymentCardNameError
                                }
                            ]
                        },
                        cvc: {
                            identifier  : 'cvc',
                            rules: [
                                {
                                    type   : 'integer[0..999]',
                                    prompt : that.strings.paymentCardCvcError
                                }
                            ]
                        },
                        expiration_year: {
                            identifier  : 'expiration_year',
                            rules: [
                                {
                                    type   : 'integer[' + new Date().getFullYear() + '..' + ((new Date().getFullYear())+50) +']',
                                    prompt : that.strings.paymentCardYearError
                                }
                            ]
                        }
                    }
                })
            ;
            $('#help-'+this._uid).popup({popup: '.special.popup', position   : 'left center'});
        },
        methods: {
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            setSteps () {
                (this.steps[2]).title = this.strings.stepThreeTitle + '(' + (this.dataInvoice.cost/100).toFixed(2) + this.strings.stepThreeTitlePost +')';
            },
            setDataCgv () {
                let htmlObject = $('<p>'+this.strings.toggleCgvLabel+'</p>');
                this.dataCgvText = htmlObject[0].firstChild.data;
                this.dataCgvA = htmlObject[0].firstElementChild.innerHTML;
                this.dataCgvHref = htmlObject[0].firstElementChild.href;
            },
            calcTVA () {
                this.tva = 0;
                if(this.dataInvoice.tvaSubject){
                    for(let index in this.dataInvoice.options){
                        this.tva = this.tva + (this.dataInvoice.options[index].tvaVal);
                    }
                }
            }
        }
    }
</script>
