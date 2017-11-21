<template>
    <div class="ui yellow inverted compact segment discount-on-total-advert">
    <template v-if="!forSeller">
            <div class="without-discount"><div><div class="stroke"></div>{{ margins.totalPriceByLotMargin + advert.currencySymbol }}</div></div>
            <div class="ui red header with-discount">
                <span class="whole-part">{{ margins.totalPriceMarginWholePart }}</span><span class="currency">{{ advert.currencySymbol }}</span><span class="decimal-part">.{{ margins.totalPriceMarginDecimalPart }}</span>
                <div class="discount-value">(-{{ margins.globalDiscount }}% )</div>
            </div>
    </template>
    <template v-else>
            <div class="without-discount"><div><div class="stroke"></div>{{ margins.totalPriceByLot + advert.currencySymbol }}</div></div>
            <div class="ui red header with-discount">
                <span class="whole-part">{{ margins.totalSellerPriceWholePart }}</span><span class="currency">{{ advert.currencySymbol }}</span><span class="decimal-part">.{{ margins.totalSellerPriceDecimalPart }}</span>
                <div class="discount-value">(-{{ margins.globalDiscount }}% )</div>
            </div>
    </template>
    </div>
</template>

<script>
  /**
   * Props
   *  - advert: Object. The advert object
   *  - margins: Object. The margin object
   *  - forSeller: Boolean. If the discount tag is for seller (with or without delegation margin)
   * Events:
   *
   */
  export default {
    props: {
      advert: Object,
      margins: Object,
      forSeller: {
        type: Boolean,
        required: false,
        default: false
      }
    }
  }
</script>
