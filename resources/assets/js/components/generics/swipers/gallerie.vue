<template>
    <div :id="_uid" style="width: 100%">
        <swiper-top class="gallery-top" :style="'height: ' + dataHeightTop + 'px;'"
                :options="swiperOptionTop"
                :video-id="videoId"
                :pictures="pictures"
                :main-picture="mainPicture"
                :lazy-load="lazyLoad"
                @openLightBox="$emit('openLightBox', $event)"
        ></swiper-top>
        <swiper-thumbs class="gallery-thumbs" :style="'height: ' + dataHeightThumb + 'px;'"
                :options="swiperOptionThumbs"
                :video-id="videoId"
                :pictures="pictures"
                :main-picture="mainPicture"
        ></swiper-thumbs>
    </div>
</template>

<script>
  export default {
    props: {
      // vue routes
      // vue vars
      pictures: {
        type: Array
      },
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
          pagination: '.swiper-pagination',
          nextButton: '.swiper-button-next',
          prevButton: '.swiper-button-prev',
          paginationType: 'progress',
          spaceBetween: 10,
          preloadImages: false,
          // Enable lazy loading
          lazyLoading: true,
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
      const swiperTop = this.$children.find((children) => children.options.name === 'swiperTop').swiper
      const swiperThumbs = this.$children.find((children) => children.options.name === 'swiperThumbs').swiper
      swiperTop.params.control = swiperThumbs
      swiperThumbs.params.control = swiperTop
      this.dataHeightTop = $('#' + this._uid).width() / this.properties.imageRatio
      this.dataHeightThumb = this.dataHeightTop * 20 / 80
    }
  }
</script>