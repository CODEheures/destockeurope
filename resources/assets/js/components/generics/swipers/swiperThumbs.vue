<template>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide" v-if="videoId != null && videoId != ''">
                <div style="width:100%; height:100%; background-color: rgba(0,0,0,0.1); position: absolute"></div>
                <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + videoId" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
           <div class="swiper-slide" v-for="picture in dataPictures" :style="'background-image:url('+picture.thumbUrl+')'"></div>
        </div>
    </div>
</template>

<script>
  /**
   * Props
   *  - options: Object. Object of swiper  options
   *  - pictures: Array. List of pictures objects
   *  - mainPicture: String. Hash of the main picture
   *  - videoId: The id of the video
   *
   * Events:
   *
   */
  import Player from '@vimeo/player'
  import Swiper from 'swiper'

  export default {
    props: {
      options: {
        type: Object,
        default: {
          autoplay: 3500
        }
      },
      pictures: Array,
      mainPicture: String,
      videoId: {
        type: Number,
        required: false,
        default: null
      }
    },
    watch: {
      mainPicture () {
        this.updateDataPictures()
      },
      pictures () {
        this.updateDataPictures()
      }
    },
    data () {
      return {
        dataPictures: []
      }
    },
    mounted () {
      if (!this.swiper && typeof global.window !== 'undefined') {
        this.swiper = new Swiper(this.$el, this.options)
      }
      this.updateDataPictures()
    },
    updated () {
      this.swiper.update()
      let iframe = document.getElementById('vimeo-iframe-' + this._uid)
      if (iframe) {
        // eslint-disable-next-line no-new
        new Player(iframe)
      }
    },
    beforeDestroy () {
      if (this.swiper) {
        this.swiper = null
        delete this.swiper
      }
    },
    methods: {
      updateDataPictures () {
        let pictures = []
        let that = this
        if (this.pictures) {
          this.pictures.forEach(function (picture) {
            if (picture.hashName === that.mainPicture) {
              pictures.unshift(picture)
            }
            else {
              pictures.push(picture)
            }
          })
        }
        this.dataPictures = pictures
      }
    }
  }
</script>