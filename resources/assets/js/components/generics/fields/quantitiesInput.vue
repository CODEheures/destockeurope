<template>
    <div :class="withValidButton ? 'two fields':'field'">
        <template v-if="withValidButton">
            <div class="field">
                <div class="two fields">
                    <div class="field">
                        <label>{{ strings.totalQuantityLabel }}</label>
                        <number-input name="totalQuantity" :min="1" :decimal="0" v-model="advert.totalQuantity" @blur="setMaxLotMini"></number-input>
                    </div>
                    <div class="field">
                        <label>{{ strings.lotMiniQuantityLabel }}</label>
                        <number-input  name="lotMiniQuantity" :min="1" :max="maxLotMini" :decimal="0" v-model="advert.lotMiniQuantity"></number-input>
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
                    <number-input name="totalQuantity" :min="1" :decimal="0" v-model="advert.totalQuantity" @blur="setMaxLotMini"></number-input>
                </div>
                <div class="field">
                    <label>{{ strings.lotMiniQuantityLabel }}</label>
                    <number-input  name="lotMiniQuantity" :min="1" :max="maxLotMini" :decimal="0" v-model="advert.lotMiniQuantity"></number-input>
                </div>
            </div>
        </template>
        <template v-if="!withValidButton && onlyMiniLot">
            <label>{{ strings.lotMiniQuantityLabel }}</label>
            <number-input  name="lotMiniQuantity" :min="1" :max="advert.totalQuantity" :decimal="0" v-model="advert.lotMiniQuantity"></number-input>
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
        data () {
            return {
                maxLotMini: null
            }
        },
        mounted () {
            this.maxLotMini = this.advert.totalQuantity;
        },
        methods: {
            updateQuantities: function () {
                let that = this;
                Axios.patch(that.advert.updateQuantitiesUrl, {'totalQuantity': that.advert.totalQuantity, 'lotMiniQuantity': that.advert.lotMiniQuantity})
                    .then(function (response) {
                        that.$emit('updateSuccess')
                    })
                    .catch(function (error) {
                        that.$emit('loadError')
                    });
            },
            setMaxLotMini () {
                this.maxLotMini = this.advert.totalQuantity
            }
        }
    }
</script>
