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
                        {{ margins.unitMargin + advert.currencySymbol }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ strings.lotMarginLabel }}
                    </td>
                    <td>
                        {{ margins.lotMiniMargin + advert.currencySymbol }}
                    </td>
                </tr>
                <tr :id="'tr_total_margin1_'+_uid" :class="margins.totalMargin <= 0 ? 'error' : ''" :data-content="margins.totalMargin <= 0 ? strings.nullOrNegativeMarginLabel : null" data-position="bottom center">
                    <td>
                        {{ strings.totalMarginLabel }}
                    </td>
                    <td>
                        {{ margins.totalMargin + advert.currencySymbol }} <i v-if="margins.totalMargin <= 0" class="warning sign icon"></i>
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
                            {{ margins.priceMargin + advert.currencySymbol }}
                        </td>
                    </tr>
                    <tr :id="'tr_lot_margin_'+_uid" :class="margins.lotMiniMargin <= 0 ? 'error' : ''" :data-content="margins.lotMiniMargin <= 0 ? strings.nullMarginLabel : null" data-position="bottom center">
                        <td>
                            {{ strings.lotMarginLabel }}
                        </td>
                        <td>
                            {{ margins.lotMiniMargin + advert.currencySymbol }} <i v-if="margins.lotMiniMargin <= 0" class="warning sign icon"></i>
                        </td>
                    </tr>
                </template>
                <template v-else>
                    <tr>
                        <td>
                            {{ strings.totalProviderPrice }}
                        </td>
                        <td>
                            {{ margins.totalSellerPrice + advert.currencySymbol }} <span v-if="advert.discount_on_total>0">(-{{ advert.discount_on_total }}%)</span>
                        </td>
                    </tr>
                    <tr :id="'tr_total_margin2_'+_uid" :class="margins.totalMargin <= 0 ? 'error' : ''" :data-content="margins.totalMargin <= 0 ? strings.nullMarginLabel : null" data-position="bottom center">
                        <td>
                            {{ strings.totalMarginLabel }}
                        </td>
                        <td>
                            {{ margins.totalMargin + advert.currencySymbol }} <i v-if="margins.totalMargin <= 0" class="warning sign icon"></i>
                        </td>
                    </tr>
                    <tr v-show="margins.coefficientTotalIsOverMax" :id="'tr_total_price_'+_uid" :class="margins.coefficientTotalIsOverMax ? 'warning' : ''" :data-content="margins.coefficientTotalIsOverMax ? strings.overPriceLabel : null" data-position="bottom center">
                        <td>
                            {{ strings.newPriceLabel }}
                        </td>
                        <td>
                            {{ margins.totalPriceMargin + advert.currencySymbol }} <i v-if="margins.coefficientTotalIsOverMax" class="info circle icon"></i>
                        </td>
                    </tr>
                    <tr v-if="!margins.coefficientTotalIsOverMax">
                        <td colspan="2">
                            <discount-tag
                                    :advert="advert"
                                    :margins="margins"
                            ></discount-tag>
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
                margins: {
                    unitMargin: 0,
                    totalMargin: 0,
                    lotMiniMargin: 0,
                    unitSellerPrice: 0,
                    priceMargin: 0,
                    totalSellerPrice: 0,
                    totalSellerPriceWholePart: 0,
                    totalSellerPriceDecimalPart: 0,
                    totalPriceMargin: 0,
                    totalPriceMarginWholePart: 0,
                    totalPriceMarginDecimalPart: 0,
                    totalPriceByLot: 0,
                    totalPriceByLotMargin: 0,
                    globalDiscount: 0,
                    coefficientTotalIsOverMax: false
                }
            }
        },
        mounted () {
            this.strings = this.$store.state.strings['margin-table'];
            let that = this;
            if(!this.forSeller){
                this.$watch('advert.price_coefficient', function () {
                    that.updateMargins();
                });
                this.$watch('advert.price_coefficient_total', function () {
                    that.updateMargins();
                });
            }
            if(this.forSeller){
                this.$watch('advert.buyingPrice', function () {
                    that.updateMargins();
                });
                this.$watch('advert.originalPrice', function () {
                    that.updateMargins();
                });
                this.$watch('advert.discount_on_total', function () {
                    that.updateMargins();
                });
            }
            this.$watch('advert.lotMiniQuantity', function () {
                that.updateMargins();
            });
            this.$watch('advert.totalQuantity', function () {
                that.updateMargins();
            });
            $('#tr_total_price_'+this._uid).popup();
            $('#tr_lot_margin_'+this._uid).popup();
            $('#tr_total_margin1_'+this._uid).popup();
            $('#tr_total_margin2_'+this._uid).popup();
            this.updateMargins();
        },
        methods: {
            updateMargins () {
                let calcMargins = DestockTools.calcMargins(this.advert, this.forSeller);
                Object.assign(this.margins, calcMargins);
                if(!this.forSeller && this.forLotMargin){
                    this.advert.price_margin = this.margins.priceMargin + this.advert.currencySymbol;
                }
            }
        }
    }
</script>
