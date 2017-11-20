<template>
    <div>
        <div :class="centered ? 'ui small rectangle centered ad' : 'ui small rectangle ad' " :style="!properties.withAdsense ? 'background-image: url(\''+img1+'\'); background-size: contain;':'background: none;'">
            <!-- square180x150_1 -->
            <adsense v-if="properties.withAdsense"
                    ad-client="ca-pub-9425842434111721"
                    ad-slot="1454079496"
                    style="display:inline-block;width:180px;height:150px"
            ></adsense>
        </div>
        <div :class="centered ? 'ui small rectangle centered ad spaced-top-2' : 'ui small rectangle ad spaced-top-2'" :style="!properties.withAdsense ? 'background-image: url(\''+img2+'\'); background-size: contain;':'background: none;'">
            <!-- square180x150_2 -->
            <adsense v-if="properties.withAdsense"
                    ad-client="ca-pub-9425842434111721"
                    ad-slot="2930812691"
                    style="display:inline-block;width:180px;height:150px"
            ></adsense>
        </div>
    </div>
</template>

<script>
  /**
   * Props
   *  - img1: String. path of background ad1
   *  - img2: String. path of background ad1
   *  - centered: Boolean. To center ad on div
   */
  export default {
    props: {
      // Vue vars
      'img1': {
        required: false,
        type: String,
        default: '/images/ads/bandeau180x150.jpg'
      },
      'img2': {
        required: false,
        type: String,
        default: '/images/ads/bandeau180x150_2.jpg'
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