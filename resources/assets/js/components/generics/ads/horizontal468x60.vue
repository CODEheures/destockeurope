<template>
    <div class="ui centered banner ad" :style="!properties.withAdsense ? 'background-image: url(\''+img1+'\'); background-size: contain;':'background: none;'">
        <!-- horizontal468x60 -->
        <adsense v-if="properties.withAdsense"
                ad-client="ca-pub-9425842434111721"
                ad-slot="5884279095"
                style="display:inline-block;width:468px;height:60px"
        ></adsense>
    </div>
</template>

<script>
  export default {
    props: {
      // Vue vars
      'img1': {
        required: false,
        type: String,
        default: '/images/ads/bandeau468x60.jpg'
      },
      'url1': {
        required: false,
        type: String,
        default: '#'
      }
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      }
    }
  }
</script>