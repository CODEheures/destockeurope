<template>
    <div :class="withValidButton ? 'three fields':'field'">
        <template v-if="withValidButton">
            <div class="field" style="margin-top: 0">
                <label>{{ strings.coefficientLabel }}</label>
                <number-input name="price_coefficient" :min="0" :decimal="2" v-model="advert.price_coefficient"></number-input>
                <margins-table
                        :advert="advert"
                ></margins-table>
            </div>
            <div class="field">
                <label>{{ strings.coefficientTotalLabel }}</label>
                <number-input name="price_coefficient_total" :min="0" :decimal="2" v-model="advert.price_coefficient_total"></number-input>
                <margins-table
                        :advert="advert"
                        :forLotMargin="false"
                ></margins-table>
            </div>
            <div class="field">
                <label style="opacity: 0">1</label>
                <div v-on:click="updateCoefficient()" class="ui primary button">
                    <i class="percent icon"></i>
                    {{ strings.coefficientUpdateLabel }}
                </div>
            </div>
        </template>
        <template v-else>
            <div class="grouped fields">
                <div class="field" style="margin-top: 0">
                    <label>{{ strings.coefficientLabel }}</label>
                    <number-input name="price_coefficient" :min="0" :decimal="2" v-model="advert.price_coefficient"></number-input>
                </div>
                <margins-table
                        :advert="advert"
                ></margins-table>
                <div class="field">
                    <label>{{ strings.coefficientTotalLabel }}</label>
                    <number-input name="price_coefficient_total" :min="0" :decimal="2" v-model="advert.price_coefficient_total"></number-input>
                </div>
                <margins-table
                        :advert="advert"
                        :forLotMargin="false"
                ></margins-table>
            </div>
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
        },
        data: () => {
            return {
                strings: {},
            }
        },
        mounted () {
            this.strings = this.$store.state.strings['margin-input-field'];
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
                axios.patch(that.advert.updateCoefficientUrl, {'coefficient': (that.advert.price_coefficient*100).toFixed(0), 'coefficient_total': (that.advert.price_coefficient_total*100).toFixed(0)})
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
