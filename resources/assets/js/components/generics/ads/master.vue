<template>
    <div v-if="isActive" class="masterads">
        <a :href="urlRedirect" target="_blank">
            <img :id="'img_masterads'+_uid" class="ui fluid image masterads" :src="datasrc">
        </a>
    </div>
</template>

<script>
  /**
   * Props
   *  - routeImageServer: String. route of the image server service (use to bypass Cross-request)
   *  - isActive: Boolean. If masterAd is active
   *  - urlImg: String. Url of the AD image
   *  - urlRedirect: String. Url of the redirect href
   *  - offsetYMainContainer: String. Offset on Y axis of the container
   *  - selectorMainContainer: String. Selector for the main container to Apply offset
   *  - width: Number. The width for the Ad
   *  - adsOffsetY: Number. Offset for the Ad
   */
  import Parser from 'url'
  export default {
    props: {
      // vue routes
      routeImageServer: String,
      // vue vars
      isActive: Boolean,
      urlImg: String,
      urlRedirect: String,
      offsetYMainContainer: String,
      selectorMainContainer: String,
      width: Number,
      adsOffsetY: Number
    },
    computed: {
      datasrc () {
        if (this.urlImg !== '') {
          let urlBase = this.routeImageServer
          let parsed = Parser.parse(urlBase, true)
          parsed.query = {}
          parsed.query['url'] = this.urlImg
          return Parser.format(parsed)
        }
        else {
          return '/images/background.jpg'
        }
      }
    },
    mounted () {
      let that = this
      this.adaptView()
      $(window).bind('resize', function () {
        that.adaptView()
      })
    },
    methods: {
      adaptView () {
        if (this.isActive) {
          let that = this
          let img = $('div.masterads')
          let mainContainer = $(that.selectorMainContainer)
          if (this.width < $(document).width()) {
            img.css({
              'display': 'block',
              'width': that.width + 'px',
              'left': 'calc(50% - ' + (that.width / 2) + 'px)',
              'top': that.adsOffsetY + 'px'
            })
            mainContainer.animate({
              'margin-top': that.offsetYMainContainer + 'px'
            }, 800)
          }
          else {
            img.css({
              'display': 'none'
            })
            mainContainer.css({
              'margin-top': ''
            })
          }
        }
      }
    }
  }
</script>
