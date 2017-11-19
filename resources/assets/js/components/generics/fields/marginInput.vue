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
  import Axios from 'axios'
  export default {
    props: {
      advert: {
        type: Object
      },
      withValidButton: {
        type: Boolean,
        required: false,
        default: true
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['margin-input-field']
      }
    },
    watch: {
      'advert.price_coefficient' () {
        this.advert.price_coefficient_total = this.advert.price_coefficient
      }
    },
    methods: {
      updateCoefficient () {
        let that = this
        Axios.patch(that.advert.updateCoefficientUrl, {'coefficient': (that.advert.price_coefficient * 100).toFixed(0), 'coefficient_total': (that.advert.price_coefficient_total * 100).toFixed(0)})
          .then(function (response) {
            that.$emit('updateSuccess')
          })
          .catch(function () {
            that.$emit('loadError')
          })
      }
    }
  }
</script>
