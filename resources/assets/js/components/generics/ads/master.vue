<template>
    <div v-if="dataIsActive" class="masterads">
        <a :href="urlRedirect" target="_blank">
            <img :id="'img_masterads'+_uid" class="ui fluid image masterads" :src="datasrc">
        </a>
    </div>
</template>

<script>
  import Parser from 'url'
  export default {
    props: [
      // vue routes
      'routeImageServer',
      // vue vars
      'isActive',
      'urlImg',
      'urlRedirect',
      'offsetYMainContainer',
      'selectorMainContainer',
      'width',
      'adsOffsetY'
    ],
    computed: {
      dataIsActive () { return this.isActive === '1' }
    },
    data () {
      return {
        datasrc: '/images/background.jpg',
        datawidth: 1400
      }
    },
    mounted () {
      this.setDatasrc()
      this.setDataWidth()
    },
    updated () {
      let that = this
      this.adaptView()
      $(window).bind('resize', function () {
        that.adaptView()
      })
    },
    methods: {
      setDatasrc () {
        if (this.urlImg !== '') {
          let urlBase = this.routeImageServer
          let parsed = Parser.parse(urlBase, true)
          parsed.query = {}
          parsed.query['url'] = this.urlImg
          this.datasrc = Parser.format(parsed)
        }
      },
      setDataWidth () {
        if (this.urlImg !== '' && this.width !== undefined && this.width !== null) {
          this.datawidth = this.width
        }
      },
      adaptView () {
        if (this.dataIsActive) {
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
