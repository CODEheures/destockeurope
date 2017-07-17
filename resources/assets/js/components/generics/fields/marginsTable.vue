<template>
    <div class="ui pointing label">
        <table class="ui very basic collapsing celled table">
            <tbody>
            <tr v-if="!forSeller">
                <td>
                    {{ formAdvertPriceCoefficientNewPriceLabel }}
                </td>
                <td>
                    {{ calcMargin(advert,3)  }}
                </td>
            </tr>
            <tr>
                <td>
                    {{ formAdvertPriceCoefficientUnitMarginLabel }}
                </td>
                <td>
                    {{ calcMargin(advert,1)  }}
                </td>
            </tr>
            <tr>
                <td>
                    {{ formAdvertPriceCoefficientLotMarginLabel }}
                </td>
                <td>
                    {{ calcMargin(advert,4)  }}
                </td>
            </tr>
            <tr>
                <td>
                    {{ formAdvertPriceCoefficientTotalMarginLabel }}
                </td>
                <td>
                    {{ calcMargin(advert,2)  }}
                </td>
            </tr>
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
            formAdvertPriceCoefficientLabel: String,
            formAdvertPriceCoefficientNewPriceLabel: String,
            formAdvertPriceCoefficientUnitMarginLabel: String,
            formAdvertPriceCoefficientLotMarginLabel: String,
            formAdvertPriceCoefficientTotalMarginLabel: String,
        },
        data: () => {
            return {

            }
        },
        mounted () {

        },
        methods: {
            calcMargin: function (advert, type) {
                let unitMargin;
                let totalMargin;
                let lotMiniMargin;
                if(this.forSeller){
                    unitMargin =  advert.originalPrice-advert.buyingPrice;
                    totalMargin = advert.totalQuantity*(advert.originalPrice*(1-(advert.discount_on_total/100))-advert.buyingPrice);
                    lotMiniMargin = unitMargin*advert.lotMiniQuantity;
                } else {
                    unitMargin =  (((advert.originalPrice*advert.price_coefficient)/(100*Math.pow(10,advert.priceSubUnit))));
                    unitMargin = (Math.floor(unitMargin*Math.pow(10,advert.priceSubUnit))/Math.pow(10,advert.priceSubUnit));
                    totalMargin = unitMargin*advert.totalQuantity;
                    lotMiniMargin = unitMargin*advert.lotMiniQuantity;
                }
                if(type == 1) {
                    return (unitMargin.toFixed(advert.priceSubUnit))+advert.currencySymbol
                } else if(type == 2) {
                    return (totalMargin.toFixed(advert.priceSubUnit))+advert.currencySymbol;
                } else if (type == 3) {
                    return (((advert.originalPrice/(Math.pow(10,advert.priceSubUnit))) + unitMargin).toFixed(advert.priceSubUnit))+advert.currencySymbol;
                } else if (type == 4) {
                    return (lotMiniMargin.toFixed(advert.priceSubUnit))+advert.currencySymbol;
                }
            }
        }
    }
</script>
