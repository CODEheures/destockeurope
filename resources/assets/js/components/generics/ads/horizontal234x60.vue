<template>
    <div class="ui centered half banner ad" :style="!properties.withAdsense ? 'background-image: url(\''+img1+'\'); background-size: contain;':'background: none;'">
        <!-- horizontal234x60 -->
        <adsense v-if="properties.withAdsense"
            ad-client="ca-pub-9425842434111721"
            ad-slot="4407545893"
            style="display:inline-block;width:234px;height:60px"
        ></adsense>
    </div>
</template>

<script>
  /**
   * Props
   *  - img1: String. path of background ad1
   */
  export default {
    props: {
      // Vue vars
      'img1': {
        required: false,
        type: String,
        default: '/images/ads/bandeau234x60.jpg'
      }
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      }
    }
  }
</script>