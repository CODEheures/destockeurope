<template>
    <div class="ui pointing label">
        <table class="ui very basic collapsing celled table">
            <tbody>
            <template v-if="forSeller">
                <tr>
                    <td>
                        {{ strings.unitMarginLabel }}
                    </td>
                    <td>
                        {{ unitMargin + advert.currencySymbol }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ strings.lotMarginLabel }}
                    </td>
                    <td>
                        {{ lotMiniMargin + advert.currencySymbol }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ strings.totalMarginLabel }}
                    </td>
                    <td>
                        {{ totalMargin + advert.currencySymbol }}
                    </td>
                </tr>
            </template>
            <template v-else>
                <template v-if="forLotMargin">
                    <tr>
                        <td>
                            {{ strings.providerPrice }}
                        </td>
                        <td>
                            {{ advert.price }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ strings.newPriceLabel }}
                        </td>
                        <td>
                            {{ priceMargin + advert.currencySymbol }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ strings.lotMarginLabel }}
                        </td>
                        <td>
                            {{ lotMiniMargin + advert.currencySymbol }}
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr>
                        <td>
                            {{ strings.totalProviderPrice }}
                        </td>
                        <td>
                            {{ totalSellerPrice + advert.currencySymbol }}
                        </td>
                    </tr>
                    <tr :id="'tr_total_price_'+_uid" :class="coefficientTotalIsOverMax ? 'error' : ''" :data-content="coefficientTotalIsOverMax ? strings.overPriceLabel : null" data-position="bottom center">
                        <td>
                            {{ strings.newPriceLabel }}
                        </td>
                        <td>
                            {{ totalPriceMargin + advert.currencySymbol }} <i v-if="coefficientTotalIsOverMax" class="warning sign icon"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ strings.totalMarginLabel }}
                        </td>
                        <td>
                            {{ totalMargin + advert.currencySymbol }}
                        </td>
                    </tr>
                </template>
            </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            advert: {
                type: Object
            },
            forSeller: {
                type: Boolean,
                required: false,
                default: false
            },
            forLotMargin: {
                type: Boolean,
                required: false,
                default: true
            }
        },
        data: () => {
            return {
                strings: {},
                coefficientTotalIsOverMax: false,
                unitMargin: 0,
                totalMargin: 0,
                lotMiniMargin: 0,
                priceMargin: 0,
                unitSellerPrice: 0,
                totalSellerPrice: 0,
                totalPriceMargin: 0
            }
        },
        mounted () {
            this.strings = this.$store.state.strings['margin-table'];
            let that = this;
            if(!this.forSeller){
                this.$watch('advert.price_coefficient', function () {
                    that.calcMargin();
                });
                this.$watch('advert.price_coefficient_total', function () {
                    that.calcMargin();
                });
            }
            if(this.forSeller){
                this.$watch('advert.buyingPrice', function () {
                    that.calcMargin();
                });
                this.$watch('advert.originalPrice', function () {
                    that.calcMargin();
                });
                this.$watch('advert.discount_on_total', function () {
                    that.calcMargin();
                });
            }
            this.$watch('advert.lotMiniQuantity', function () {
                that.calcMargin();
            });
            this.$watch('advert.totalQuantity', function () {
                that.calcMargin();
            });
            $('#tr_total_price_'+this._uid).popup();
            this.calcMargin();
        },
        methods: {
            calcMargin: function () {
                let originalUnitSellerPrice = this.advert.originalPrice;
                let originalTotalSellerPrice = Math.floor(this.advert.totalQuantity*(this.advert.originalPrice*(1-(this.advert.discount_on_total/100))));

                let unitSellerPrice = originalUnitSellerPrice/(Math.pow(10,this.advert.priceSubUnit));
                let totalSellerPrice = originalTotalSellerPrice/(Math.pow(10,this.advert.priceSubUnit));

                let unitMargin=0;
                let totalMargin=0;
                let lotMiniMargin=0;
                let priceMargin=0;
                let totalPriceMargin=0;

                if(this.forSeller){
                    unitMargin =  this.advert.originalPrice-this.advert.buyingPrice;
                    unitMargin = Math.floor(unitMargin)/Math.pow(10,this.advert.priceSubUnit);

                    lotMiniMargin = unitMargin*this.advert.lotMiniQuantity;

                    totalMargin = originalTotalSellerPrice-this.advert.buyingPrice*this.advert.totalQuantity;
                    totalMargin = Math.floor(totalMargin)/Math.pow(10,this.advert.priceSubUnit);
                } else {
                    unitMargin =  this.advert.originalPrice*this.advert.price_coefficient/100;
                    unitMargin = Math.floor(unitMargin)/Math.pow(10,this.advert.priceSubUnit);

                    lotMiniMargin = unitMargin*this.advert.lotMiniQuantity;

                    totalMargin = originalTotalSellerPrice*this.advert.price_coefficient_total/100;
                    totalMargin = Math.floor(totalMargin)/Math.pow(10,this.advert.priceSubUnit);

                    priceMargin = unitSellerPrice + unitMargin;
                    totalPriceMargin = totalSellerPrice + totalMargin;
                }

                this.coefficientTotalIsOverMax = totalPriceMargin > priceMargin*this.advert.totalQuantity;


                this.unitMargin = unitMargin.toFixed(this.advert.priceSubUnit);
                this.totalMargin = totalMargin.toFixed(this.advert.priceSubUnit);
                this.lotMiniMargin = lotMiniMargin.toFixed(this.advert.priceSubUnit);
                this.unitSellerPrice = unitSellerPrice.toFixed(this.advert.priceSubUnit);
                this.priceMargin = priceMargin.toFixed(this.advert.priceSubUnit);
                this.totalSellerPrice = totalSellerPrice.toFixed(this.advert.priceSubUnit);
                this.totalPriceMargin = totalPriceMargin.toFixed(this.advert.priceSubUnit);

            }
        }
    }
</script>
