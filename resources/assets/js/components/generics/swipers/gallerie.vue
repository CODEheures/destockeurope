<template>
    <div :id="_uid" style="width: 100%">
        <swiper-top class="gallery-top" :style="'height: ' + dataHeightTop + 'px;'" ref="swiperTop"
                :options="swiperOptionTop"
                :video-id="videoId"
                :pictures="pictures"
                :main-picture="mainPicture"
                :lazy-load="lazyLoad"
                @openLightBox="$emit('openLightBox', $event)"
        ></swiper-top>
        <swiper-thumbs class="gallery-thumbs" :style="'height: ' + dataHeightThumb + 'px;'" ref="swiperThumbs"
                :options="swiperOptionThumbs"
                :video-id="videoId"
                :pictures="pictures"
                :main-picture="mainPicture"
        ></swiper-thumbs>
    </div>
</template>

<script>
  /**
   * Props
   *  - pictures: Array. List of picture object (with hashName, normalUrl, thumbUrl...)
   *  - mainPicture: String. HashName of the main picture
   *  - videoId: The id of the video
   *  - lazyload: Boolean. To choice if lazy load or not
   *
   * Events:
   *  @openLightBox: emit when click on image to open a ligthBox
   *
   */
  export default {
    props: {
      pictures: Array,
      mainPicture: {
        type: String,
        required: false,
        default: ''
      },
      videoId: {
        type: Number,
        required: false,
        default: null
      },
      lazyLoad: {
        type: Boolean,
        required: false,
        default: true
      }
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      },
      swiperOptionTop () {
        return {
          name: 'swiperTop',
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
          },
          pagination: {
            el: '.swiper-pagination',
            type: 'progressbar'
          },
          spaceBetween: 10,
          preloadImages: false,
          // Enable lazy loading
          lazy: {
            loadPrevNext: true
          },
          observer: true,
          zoom: true
        }
      },
      swiperOptionThumbs () {
        return {
          name: 'swiperThumbs',
          spaceBetween: 10,
          centeredSlides: true,
          slidesPerView: 'auto',
          touchRatio: 0.2,
          slideToClickedSlide: true
        }
      }
    },
    data () {
      return {
        dataHeightTop: 500,
        dataHeightThumb: 100
      }
    },
    mounted () {
      this.dataHeightTop = $('#' + this._uid).width() / this.properties.imageRatio
      this.dataHeightThumb = this.dataHeightTop * 20 / 80
      this.$nextTick(() => {
        const swiperTop = this.$refs.swiperTop.swiper
        const swiperThumbs = this.$refs.swiperThumbs.swiper
        swiperTop.controller.control = swiperThumbs
        swiperThumbs.controller.control = swiperTop
      })
    }
  }
</script>