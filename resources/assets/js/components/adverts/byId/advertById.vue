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
                    @openLightBox="openLightBox"
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
                         :data-href="properties.routeFacebookSharer"
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
                                        <td><span class="ui small blue tag label">{{ advert.isNegociated ? strings.isNegociatedLabel + '(' + advert.currencySymbol + ')' : margins.priceMargin + advert.currencySymbol }}</span><br/></td>
                                    </tr>
                                    <tr v-if="!margins.coefficientTotalIsOverMax">
                                        <td class="collapsing">
                                            <i class="gift icon"></i> {{ strings.CompletePriceLabel }}
                                        </td>
                                        <td>
                                            <discount-tag
                                                :advert="advert"
                                                :margins="margins"
                                            ></discount-tag>
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
                                        <td><span class="ui small blue tag label">{{ advert.isNegociated ? strings.isNegociatedLabel + '(' + advert.currencySymbol + ')' : margins.priceMargin +advert.currencySymbol }}</span><br/></td>
                                    </tr>
                                    <tr v-if="!advert.isNegociated && margins.globalDiscount > 0">
                                        <td class="collapsing">
                                            <i class="gift icon"></i> {{ strings.CompletePriceLabel }}
                                        </td>
                                        <td>
                                            <div class="ui yellow inverted compact segment discount-on-total-advert">
                                                <div class="without-discount"><div><div class="stroke"></div>{{ margins.totalPriceByLotMargin + advert.currencySymbol }}</div></div>
                                                <div class="ui red header with-discount">
                                                    <span class="whole-part">{{ margins.totalPriceMarginWholePart }}</span><span class="currency">{{ advert.currencySymbol }}</span><span class="decimal-part">.{{ margins.totalPriceMarginDecimalPart }}</span>
                                                    <div class="discount-value">(-{{ margins.globalDiscount }}% )</div>
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
                                    <p class="in-by-id">{{ advert.description }}</p>
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
  import moment from 'moment'
  import { DestockTools } from '../../../destockTools'
  export default {
    props: {
      // vue routes
      // vue vars
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
    computed: {
      strings () {
        return this.$store.state.strings['advert-by-id']
      },
      properties () {
        return this.$store.state.properties['global']
      }
    },
    data () {
      return {
        isLoaded: false,
        dataLightBoxUrl: '',
        dataHeight: '',
        dataVideoId: '',
        margins: {
          unitMargin: 0,
          totalMargin: 0,
          lotMiniMargin: 0,
          unitSellerPrice: 0,
          priceMargin: 0,
          totalSellerPrice: 0,
          totalSellerPriceWholePart: 0,
          totalSellerPriceDecimalPart: 0,
          totalPriceMargin: 0,
          totalPriceMarginWholePart: 0,
          totalPriceMarginDecimalPart: 0,
          totalPriceByLot: 0,
          totalPriceByLotMargin: 0,
          globalDiscount: 0,
          coefficientTotalIsOverMax: false
        }
      }
    },
    mounted () {
      let that = this
      this.dataHeight = $('#modal-' + this._uid).width() / this.properties.imageRatio
      if (!this.isStatic) {
        this.$watch('advert.price_coefficient', function () {
          that.updateMargins()
        })
        this.$watch('advert.price_coefficient_total', function () {
          that.updateMargins()
        })
        this.$watch('advert.price_margin', function () {
          that.updateMargins()
        })
        this.$watch('advert.totalPriceMargin', function () {
          that.updateMargins()
        })
      }
    },
    updated () {
      this.updateMargins()
    },
    methods: {
      getMoment (dateTime) {
        moment.locale(this.properties.actualLocale)
        return moment(dateTime).fromNow()
      },
      openLightBox (imgUrl) {
        this.dataLightBoxUrl = imgUrl
        this.dataHeight = $('.lightBox').width / this.properties.imageRatio
        $('#modal-' + this._uid).modal({
          closable: true,
          blurring: false
        }).modal('show')
      },
      updateMargins () {
        let calcMargins = DestockTools.calcMargins(this.advert, false)
        Object.assign(this.margins, calcMargins)
      }
    }
  }
</script>
