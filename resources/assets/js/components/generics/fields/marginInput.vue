<template>
    <div :class="withValidButton ? 'three fields':'field'">
        <template v-if="withValidButton">
            <div class="field" style="margin-top: 0">
                <label>{{ strings.coefficientLabel }}</label>
                <number-input name="price_coefficient" :min="0" :decimal="2" v-model="dataAdvert.price_coefficient"></number-input>
                <margins-table
                        :advert="dataAdvert"
                ></margins-table>
            </div>
            <div class="field">
                <label>{{ strings.coefficientTotalLabel }}</label>
                <number-input name="price_coefficient_total" :min="0" :decimal="2" v-model="dataAdvert.price_coefficient_total"></number-input>
                <margins-table
                        :advert="dataAdvert"
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
                    <number-input name="price_coefficient" :min="0" :decimal="2" v-model="dataAdvert.price_coefficient"></number-input>
                </div>
                <margins-table
                        :advert="dataAdvert"
                ></margins-table>
                <div class="field">
                    <label>{{ strings.coefficientTotalLabel }}</label>
                    <number-input name="price_coefficient_total" :min="0" :decimal="2" v-model="dataAdvert.price_coefficient_total"></number-input>
                </div>
                <margins-table
                        :advert="dataAdvert"
                        :forLotMargin="false"
                ></margins-table>
            </div>
        </template>
    </div>
</template>

<script>
  /**
   * Model
   *  - advert: Object. The advert object to process margins
   *
   * Props
   *  - withValidButton: Boolean. If update values is doing with a valid button
   *
   * Events:
   *
   */
  import Axios from 'axios'
  import _ from 'lodash'
  export default {
    model: {
      prop: 'advert',
      event: 'change'
    },
    props: {
      advert: Object,
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
      'advert.totalQuantity' () {
        this.dataAdvert.totalQuantity = this.advert.totalQuantity
      },
      'advert.lotMiniQuantity' () {
        this.dataAdvert.lotMiniQuantity = this.advert.lotMiniQuantity
      },
      'dataAdvert.price_coefficient' () {
        this.dataAdvert.price_coefficient_total = this.dataAdvert.price_coefficient
        this.$emit('change', this.dataAdvert)
      },
      'dataAdvert.price_coefficient_total' () {
        this.$emit('change', this.dataAdvert)
      }
    },
    data () {
      return {
        dataAdvert: _.cloneDeep(this.advert)
      }
    },
    methods: {
      updateCoefficient () {
        let that = this
        Axios.patch(that.advert.updateCoefficientUrl, {'coefficient': (that.dataAdvert.price_coefficient * 100).toFixed(0), 'coefficient_total': (that.dataAdvert.price_coefficient_total * 100).toFixed(0)})
          .then(function () {
            that.$alertV({'message': that.strings.updateSuccessMessage, 'type': 'success'})
          })
          .catch(function () {
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      }
    }
  }
</script>
