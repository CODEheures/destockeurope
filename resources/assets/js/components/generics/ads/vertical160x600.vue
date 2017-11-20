<template>
    <div :class="centered==true ? 'ui centered wide skyscraper ad welcome-ads' : 'ui wide skyscraper ad welcome-ads'" :style="!properties.withAdsense ? 'background-image: url(\''+img1+'\'); background-size: contain;':'background: none;'">
        <!-- vertical160x600 -->
        <adsense v-if="properties.withAdsense"
                ad-client="ca-pub-9425842434111721"
                ad-slot="9814951091"
                style="display:inline-block;width:160px;height:600px"
        ></adsense>
    </div>
</template>

<script>
  /**
   * Props
   *  - img1: String. path of background ad1
   *  - centered: Boolean. To center ad on div
   */
  export default {
    props: {
      // Vue vars
      'img1': {
        required: false,
        type: String,
        default: '/images/ads/bandeau160x600.jpg'
      },
      'centered': {
        required: false,
        type: Boolean,
        default: false
      }
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      }
    }
  }
</script>