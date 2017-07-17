<template>
    <div :class="withValidButton ? 'two fields':'field'">
        <template v-if="withValidButton">
            <div class="grouped fields">
                <div class="field" style="margin-top: 0">
                    <label>{{ formAdvertPriceCoefficientLabel }}</label>
                    <input type="number" name="price_coefficient" min="0" step="0.01" v-model="advert.price_coefficient">
                </div>
                <div class="field">
                    <label>{{ formAdvertPriceCoefficientTotalLabel }}</label>
                    <input type="number" name="price_coefficient_total" min="0" step="0.01" v-model="advert.price_coefficient_total">
                </div>
            </div>
            <margins-table
                    :advert="advert"
                    :formAdvertPriceCoefficientNewPriceLabel="formAdvertPriceCoefficientNewPriceLabel"
                    :formAdvertPriceCoefficientUnitMarginLabel="formAdvertPriceCoefficientUnitMarginLabel"
                    :formAdvertPriceCoefficientLotMarginLabel="formAdvertPriceCoefficientLotMarginLabel"
                    :formAdvertPriceCoefficientTotalMarginLabel="formAdvertPriceCoefficientTotalMarginLabel"
            ></margins-table>
            <div class="field">
                <label style="opacity: 0">1</label>
                <div v-on:click="updateCoefficient()" class="ui primary button">
                    <i class="percent icon"></i>
                    {{ formAdvertPriceCoefficientUpdateLabel }}
                </div>
            </div>
        </template>
        <template v-else>
            <div class="grouped fields">
                <div class="field" style="margin-top: 0">
                    <label>{{ formAdvertPriceCoefficientLabel }}</label>
                    <input type="number" name="price_coefficient" min="0" step="0.01" v-model="advert.price_coefficient">
                </div>
                <div class="field">
                    <label>{{ formAdvertPriceCoefficientTotalLabel }}</label>
                    <input type="number" name="price_coefficient_total" min="0" step="0.01" v-model="advert.price_coefficient_total">
                </div>
            </div>
            <margins-table
                    :advert="advert"
                    :formAdvertPriceCoefficientNewPriceLabel="formAdvertPriceCoefficientNewPriceLabel"
                    :formAdvertPriceCoefficientUnitMarginLabel="formAdvertPriceCoefficientUnitMarginLabel"
                    :formAdvertPriceCoefficientLotMarginLabel="formAdvertPriceCoefficientLotMarginLabel"
                    :formAdvertPriceCoefficientTotalMarginLabel="formAdvertPriceCoefficientTotalMarginLabel"
            ></margins-table>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            advert: {
                type: Object
            },
            withValidButton: {
                type: Boolean,
                required: false,
                default: true
            },
            formAdvertPriceCoefficientLabel: String,
            formAdvertPriceCoefficientTotalLabel: String,
            formAdvertPriceCoefficientNewPriceLabel: String,
            formAdvertPriceCoefficientUnitMarginLabel: String,
            formAdvertPriceCoefficientLotMarginLabel: String,
            formAdvertPriceCoefficientTotalMarginLabel: String,
            formAdvertPriceCoefficientUpdateLabel: String
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
                let lotMiniMargin = unitMargin*advert.lotMiniQuantity;
                if(type == 1) {
                    return (unitMargin.toFixed(advert.priceSubUnit))+advert.currencySymbol
                } else if(type == 2) {
                    return (totalMargin.toFixed(advert.priceSubUnit))+advert.currencySymbol;
                } else if (type == 3) {
                    return (((advert.originalPrice/(Math.pow(10,advert.priceSubUnit))) + unitMargin).toFixed(advert.priceSubUnit))+advert.currencySymbol;
                } else if (type == 4) {
                    return (lotMiniMargin.toFixed(advert.priceSubUnit))+advert.currencySymbol;
                }
            },
            updateCoefficient: function () {
                let that = this;
                axios.patch(that.advert.updateCoefficientUrl, {'coefficient': (that.advert.price_coefficient*100).toFixed(0)})
                    .then(function (response) {
                        that.$parent.$emit('updateSuccess')
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError')
                    });
            }
        }
    }
</script>
