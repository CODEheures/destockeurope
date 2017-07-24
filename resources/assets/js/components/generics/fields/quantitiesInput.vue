<template>
    <div :class="withValidButton ? 'two fields':'field'">
        <template v-if="withValidButton">
            <div class="field">
                <div class="two fields">
                    <div class="field">
                        <label>{{ strings.totalQuantityLabel }}</label>
                        <number-input name="totalQuantity" :min="1" :decimal="0" v-model="advert.totalQuantity" emitOnBlur="setMaxLotMini"></number-input>
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
                    <number-input name="totalQuantity" :min="1" :decimal="0" v-model="advert.totalQuantity" emitOnBlur="setMaxLotMini"></number-input>
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
        data: () => {
            return {
                strings: {},
                maxLotMini: null
            }
        },
        mounted () {
            this.strings = this.$store.state.strings['quantities-input-field'];
            this.$on('setMaxLotMini', function () {
                this.maxLotMini = this.advert.totalQuantity;
            });
            this.maxLotMini = this.advert.totalQuantity;
        },
        methods: {
            updateQuantities: function () {
                let that = this;
                axios.patch(that.advert.updateQuantitiesUrl, {'totalQuantity': that.advert.totalQuantity, 'lotMiniQuantity': that.advert.lotMiniQuantity})
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
