<template>
    <div class="field">
        <label>{{ formAdvertPriceCoefficientLabel }}</label>
        <input type="number" name="price_coefficient" min="0" step="0.01" v-model="advert.price_coefficient">
        <div class="ui pointing label">
            <table class="ui very basic collapsing celled table">
                <tbody>
                <tr>
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
                        {{ formAdvertPriceCoefficientTotalMarginLabel }}
                    </td>
                    <td>
                        {{ calcMargin(advert,2)  }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            advert: {
                type: Object
            },
            formAdvertPriceCoefficientLabel: String,
            formAdvertPriceCoefficientNewPriceLabel: String,
            formAdvertPriceCoefficientUnitMarginLabel: String,
            formAdvertPriceCoefficientTotalMarginLabel: String
        },
        data: () => {
            return {

            }
        },
        mounted () {

        },
        methods: {
            calcMargin: function (advert, type) {
                let unitMargin =  (((advert.originalPrice*advert.price_coefficient)/(100*Math.pow(10,advert.priceSubUnit))));
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
