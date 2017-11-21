<template>
    <div :id="'langChoice-'+_uid" :class="'ui top right pointing dropdown right floated ' + color + ' ' + size + ' button'">
        <div class="text">{{ properties.actualLocale }}</div>
        <!--<i class="dropdown icon"></i>-->
        <div class="menu">
            <div v-for="lang in dataListAvailableLang" class="item" :data-value="lang" v-on:click="goTo(lang)">{{ lang }}</div>
        </div>
    </div>
</template>


<script>
  /**
   * Props
   *  - listAvailableLang: String. Json of the list of languages: "['fr', 'en', ...]"
   *  - size: String. Size of the dropdown: mini or tiny or huge ... (see semantic-ui sizes)
   *  - color: String. Color of the dropdown: primary or blue or purple ... (see semantic-ui sizes)
   *
   * Events:
   *
   */
  import { DestockTools } from '../../../destockTools'
  export default {
    props: {
      listAvailableLang: String,
      size: {
        type: String,
        default: '',
        required: false
      },
      color: {
        type: String,
        default: 'basic',
        required: false
      }
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      },
      dataListAvailableLang () {
        return JSON.parse(this.listAvailableLang)
      }
    },
    methods: {
      goTo (lang) {
        let linkHreflang = $('link[hreflang|=' + lang + ']')
        DestockTools.goToUrl((linkHreflang.attr('href')))
      }
    },
    mounted () {
      $('#langChoice-' + this._uid).dropdown()
    }
  }
</script>
