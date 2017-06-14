<template>
    <div :class="withValidButton ? 'two fields':'field'">
        <template v-if="withValidButton">
            <div class="field">
                <div class="two fields">
                    <div class="field">
                        <label>{{ totalQuantityLabel }}</label>
                        <input type="number" name="totalQuantity" :min="Math.max(1,advert.lotMiniQuantity)" step="1" v-model="advert.totalQuantity">
                    </div>
                    <div class="field">
                        <label>{{ lotMiniQuantityLabel }}</label>
                        <input type="number" name="lotMiniQuantity" min="1" :max="advert.totalQuantity" step="1" v-model="advert.lotMiniQuantity">
                    </div>
                </div>
            </div>
            <div class="field">
                <label style="opacity: 0">1</label>
                <div v-on:click="updateQuantities()" class="ui primary button">
                    <i class="cubes icon"></i>
                    {{ formUpdateLabel }}
                </div>
            </div>
        </template>
        <template v-if="!withValidButton && !onlyMiniLot">
            <div class="two fields">
                <div class="field">
                    <label>{{ totalQuantityLabel }}</label>
                    <input type="number" name="totalQuantity" :min="Math.max(1,advert.lotMiniQuantity)" step="1" v-model="advert.totalQuantity">
                </div>
                <div class="field">
                    <label>{{ lotMiniQuantityLabel }}</label>
                    <input type="number" name="lotMiniQuantity" min="1" :max="advert.totalQuantity" step="1" v-model="advert.lotMiniQuantity">
                </div>
            </div>
        </template>
        <template v-if="!withValidButton && onlyMiniLot">
            <label>{{ lotMiniQuantityLabel }}</label>
            <input type="number" name="lotMiniQuantity" min="1" :max="advert.totalQuantity" step="1" v-model="advert.lotMiniQuantity">
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            advert: {
                type: Object
            },
            totalQuantityLabel: String,
            lotMiniQuantityLabel: String,
            formUpdateLabel: String,
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

            }
        },
        mounted () {

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
