<template>
    <div class="ui grid">
        <div :id="'modal-'+_uid" class="ui modal">
            <i class="close icon"></i>
            <div class="image content">
                <img class="image lightbox" :src="dataLightBoxUrl" :style="'height: ' + dataHeight + 'px;'">
            </div>
        </div>
        <div class="sixteen wide column">
            <swiper-gallerie
                    :pictures="advert.pictures"
                    :main-picture="advert.mainPicture"
                    :video-id="advert.video_id"
            ></swiper-gallerie>
        </div>
        <div class="sixteen wide column">
            <div class="ui grid">
                <div class="sixteen wide right aligned column geodate-computer">
                    <p>
                        <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                        <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.online_at) }}</span>
                        <i class="unhide icon"></i><span class="meta">{{ advert.views }}</span>
                    </p>
                </div>
                <div class="mobile only tablet only sixteen wide right aligned column"  v-if="userName == '' || isUserOwner">
                    <div class="fb-share-button"
                         :data-href="advert.url"
                         data-layout="button"
                         data-size="large"
                    ></div>
                    <div class="ui labeled button disabled-bookmark">
                        <div class="ui yellow button">
                            <i class="heart icon"></i>
                        </div>
                        <a class="ui basic yellow left pointing label">
                            {{ advert.bookmarkCount }}
                        </a>
                    </div>
                </div>
                <div class="sixteen wide column">
                    <div class="ui segment">
                        <div class="ui grid">
                            <div class="sixteen wide tablet only sixteen wide computer only column">
                                <table id="table-advert-infos" class="ui very basic celled table advert-infos">
                                    <tbody>
                                    <tr v-if="advert.manu_ref">
                                        <td class="collapsing">
                                            <i class="barcode icon"></i> {{ strings.refLabel }}
                                        </td>
                                        <td>{{ advert.manu_ref }}</td>
                                    </tr>
                                    <tr>
                                        <td class="collapsing">
                                            <i class="cubes icon"></i> {{ strings.totalQuantityLabel }}
                                        </td>
                                        <td>{{ advert.totalQuantity }}</td>
                                    </tr>
                                    <tr>
                                        <td class="collapsing">
                                            <i class="cube icon"></i> {{ strings.lotMiniQuantityLabel }}
                                        </td>
                                        <td>{{ advert.lotMiniQuantity }}</td>
                                    </tr>
                                    <tr>
                                        <td class="collapsing">
                                            <i class="money icon"></i> {{ strings.priceLabel }}
                                        </td>
                                        <td><span class="ui small blue tag label">{{ advert.isNegociated ? strings.isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price_margin }}</span><br/></td>
                                    </tr>
                                    <tr v-if="advert.globalDiscount > 0">
                                        <td class="collapsing">
                                            <i class="gift icon"></i> {{ strings.discountOnTotalLabel }}
                                        </td>
                                        <td>
                                            <div class="ui mini horizontal statistic">
                                                <div class="value"><i class="minus icon"></i>{{ advert.globalDiscount }}%</div>
                                                <div class="label">
                                                    ({{ advert.totalPriceMargin }})
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sixteen wide mobile only column">
                                <table class="ui very basic unstackable table">
                                    <tbody>
                                    <tr v-if="advert.manu_ref">
                                        <td class="collapsing">
                                            <i class="barcode icon"></i> {{ strings.refLabel }}
                                        </td>
                                        <td>{{ advert.manu_ref }}</td>
                                    </tr>
                                    <tr>
                                        <td class="collapsing">
                                            <i class="cubes icon"></i> {{ strings.totalQuantityLabel }}
                                        </td>
                                        <td>{{ advert.totalQuantity }}</td>
                                    </tr>
                                    <tr>
                                        <td class="collapsing">
                                            <i class="cube icon"></i> {{ strings.lotMiniQuantityLabel }}
                                        </td>
                                        <td>{{ advert.lotMiniQuantity }}</td>
                                    </tr>
                                    <tr>
                                        <td class="collapsing">
                                            <i class="money icon"></i> {{ strings.priceLabel }}
                                        </td>
                                        <td><span class="ui small blue tag label">{{ advert.isNegociated ? strings.isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price_margin }}</span><br/></td>
                                    </tr>
                                    <tr v-if="!advert.isNegociated && advert.globalDiscount > 0">
                                        <td class="collapsing">
                                            <i class="gift icon"></i> {{ strings.discountOnTotalLabel }}
                                        </td>
                                        <td>
                                            <div class="ui mini statistic">
                                                <div class="value"><i class="minus icon"></i>{{ advert.globalDiscount }}%</div>
                                                <div class="label">
                                                    ({{ advert.totalPriceMargin }})
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="ui hidden divider"></div>
                            <div class="sixteen wide column item-description">
                                <div class="description">
                                    <p>{{ advert.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            //vue routes
            //vue vars
            advert: Object,
            userName: {
                type: String,
                default: undefined,
                required: false
            },
            isUserOwner: {
                type: Boolean,
                default: false,
                required: false
            },
            isStatic: {
                type: Boolean,
                default: true,
                required: false
            }
        },
        data: () => {
            return {
                strings: {},
                properties: {},
                isLoaded: false,
                dataLightBoxUrl: '',
                dataHeight: '',
                dataVideoId: '',
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['advert-by-id'];
            this.properties = this.$store.state.properties['global'];
            let that = this;
            this.$on('openLightBox', function (imgUrl) {
                this.openLightBox(imgUrl);
            });
            this.dataHeight = $('#modal-'+this._uid).width()/this.properties.imageRatio;

            if(!this.isStatic){
                this.$watch('advert.price_coefficient', function () {
                    that.advert.price_margin = that.calcUnitPrice() + this.advert.currencySymbol;
                });
                this.$watch('advert.price_coefficient_total', function () {
                    that.advert.totalPriceMargin = that.calcTotalPrice() + this.advert.currencySymbol;
                });
                this.$watch('advert.price_margin', function () {
                    that.advert.globalDiscount = that.calcGlobalDiscount();
                });
                this.$watch('advert.totalPriceMargin', function () {
                    that.advert.globalDiscount = that.calcGlobalDiscount();
                });
                that.advert.globalDiscount = that.calcGlobalDiscount();
            }
        },
        methods: {
            getMoment: function (dateTime) {
                moment.locale(this.properties.actualLocale);
                return moment(dateTime).fromNow()
            },
            openLightBox: function (imgUrl) {
                this.dataLightBoxUrl = imgUrl;
                this.dataHeight = $('.lightBox').width/this.properties.imageRatio;
                $('#modal-'+this._uid).modal({
                    closable: true,
                    blurring: true
                }).modal('show');
            },
            calcUnitPrice: function () {
                let unitMargin =  this.advert.originalPrice*this.advert.price_coefficient/100;
                unitMargin = Math.floor(unitMargin);

                let price_margin = this.advert.originalPrice + unitMargin;
                price_margin = (price_margin/(Math.pow(10,this.advert.priceSubUnit))).toFixed(this.advert.priceSubUnit);

                return price_margin;
            },
            calcTotalPrice: function () {
                let totalSellerPrice = Math.floor(this.advert.totalQuantity*(this.advert.originalPrice*(1-(this.advert.discount_on_total/100))));
                let totalMargin = totalSellerPrice*this.advert.price_coefficient_total/100;
                totalMargin = Math.floor(totalMargin);

                let price_margin_total = totalSellerPrice + totalMargin;
                price_margin_total = (price_margin_total/(Math.pow(10,this.advert.priceSubUnit))).toFixed(this.advert.priceSubUnit);

                return price_margin_total;
            },
            calcGlobalDiscount: function () {
//                let newCoef = (100+parseFloat(this.advert.price_coefficient_total))*(100-this.advert.discount_on_total);
//                newCoef = newCoef/(100+parseFloat(this.advert.price_coefficient));
//                return (100 - newCoef).toFixed(2);
                return (100- (this.calcTotalPrice()*100/(this.calcUnitPrice()*this.advert.totalQuantity))).toFixed(2);
            }
        }
    }
</script>
