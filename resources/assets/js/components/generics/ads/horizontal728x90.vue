<template>
    <div class="ui leaderboard ad" :style="!properties.withAdsense ? 'background-image: url(\''+img1+'\'); background-size: contain;':'background: none;'">
        <!-- horizontal728x90 -->
        <adsense v-if="properties.withAdsense"
                ad-client="ca-pub-9425842434111721"
                ad-slot="7361012294"
                style="display:inline-block;width:728px;height:90px"
        ></adsense>
    </div>
</template>

<script>
  /**
   * Props
   *  - 'img1': String. path of background ad1
   */
  export default {
    props: {
      // Vue vars
      'img1': {
        required: false,
        type: String,
        default: '/images/ads/bandeau728x90.jpg'
      }
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      }
    }
  }
</script>