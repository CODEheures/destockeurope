<template>
    <div :class="withValidButton ? 'two fields':'field'">
        <template v-if="withValidButton">
            <div class="field">
                <div class="two fields">
                    <div class="field">
                        <label>{{ strings.totalQuantityLabel }}</label>
                        <number-input name="totalQuantity" :min="1" :decimal="0" v-model="dataAdvert.totalQuantity" @blur="setMaxLotMini"></number-input>
                    </div>
                    <div class="field">
                        <label>{{ strings.lotMiniQuantityLabel }}</label>
                        <number-input  name="lotMiniQuantity" :min="1" :max="maxLotMini" :decimal="0" v-model="dataAdvert.lotMiniQuantity"></number-input>
                    </div>
                </div>
            </div>
            <div class="field">
                <label style="opacity: 0">1</label>
                <div v-on:click="updateQuantities()" class="ui primary button">
                    <i class="cubes icon"></i>
                    {{ strings.formUpdateLabel }}
                </div>
            </div>
        </template>
        <template v-if="!withValidButton && !onlyMiniLot">
            <div class="two fields">
                <div class="field">
                    <label>{{ strings.totalQuantityLabel }}</label>
                    <number-input name="totalQuantity" :min="1" :decimal="0" v-model="dataAdvert.totalQuantity" @blur="setMaxLotMini"></number-input>
                </div>
                <div class="field">
                    <label>{{ strings.lotMiniQuantityLabel }}</label>
                    <number-input  name="lotMiniQuantity" :min="1" :max="maxLotMini" :decimal="0" v-model="dataAdvert.lotMiniQuantity"></number-input>
                </div>
            </div>
        </template>
        <template v-if="!withValidButton && onlyMiniLot">
            <label>{{ strings.lotMiniQuantityLabel }}</label>
            <number-input  name="lotMiniQuantity" :min="1" :max="dataAdvert.totalQuantity" :decimal="0" v-model="dataAdvert.lotMiniQuantity"></number-input>
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
   *  - onlyMiniLot: Boolean. To see only the lot mini input
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
      },
      onlyMiniLot: {
        type: Boolean,
        required: false,
        default: false
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['quantities-input-field']
      }
    },
    watch: {
      'dataAdvert.totalQuantity' () {
        this.$emit('change', this.dataAdvert)
      },
      'dataAdvert.lotMiniQuantity' () {
        this.$emit('change', this.dataAdvert)
      }
    },
    data () {
      return {
        dataAdvert: _.cloneDeep(this.advert),
        maxLotMini: null
      }
    },
    mounted () {
      this.maxLotMini = this.advert.totalQuantity
    },
    methods: {
      updateQuantities () {
        let that = this
        Axios.patch(that.advert.updateQuantitiesUrl, {'totalQuantity': that.dataAdvert.totalQuantity, 'lotMiniQuantity': that.dataAdvert.lotMiniQuantity})
          .then(function (response) {
            that.$alertV({'message': that.strings.updateSuccessMessage, 'type': 'success'})
          })
          .catch(function () {
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      },
      setMaxLotMini () {
        this.maxLotMini = this.dataAdvert.totalQuantity
      }
    }
  }
</script>
