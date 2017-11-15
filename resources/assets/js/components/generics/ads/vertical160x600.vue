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
    export default {
        props: {
            //Vue vars
            'img1': {
                required: false,
                type: String,
                default: '/images/ads/bandeau160x600.jpg'
            },
            'url1': {
                required: false,
                type: String,
                default: '#'
            },
            'centered': {
                required: false,
                type: Boolean,
                default:false
            },
            'reload': {
                required: false,
                type: Boolean,
                default:true
            }
        },
        computed: {
            properties () {
                return this.$store.state.properties['global']
            }
        },
        watch: {
          reload () {
              if (this.isInitial) {
                  this.isInitial = false;
              } else {
                  console.log('reload ads vertical160x600');
              }
          }
        },
        data () {
            return {
                isInitial: true
            }
        }
    }
</script>