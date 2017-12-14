<template>
    <search-filter
      :route-search="nextUrl"
      :place-holder="strings.searchPlaceHolder"
      results-for=""
      :update="false"
      :flag-reset="false"
      :fluid="false"
      :fields="{title: 'titleWithManuRef', description : 'resume', image: 'thumb', price: 'price_margin'}"
      @clearSearchResults="clearSearchResults"
      @refreshResults="refreshResults"
    ></search-filter>
</template>

<script>
  /**
   * Props
   *
   * Events:
   *
   */
  import _ from 'lodash'
  import { DestockTools } from '../../destockTools'
  import Axios from 'axios'
  export default {
    computed: {
      strings () {
        return this.$store.state.strings['menu-filter']
      },
      properties () {
        return this.$store.state.properties['global']
      }
    },
    data () {
      return {
        nextUrl: ''
      }
    },
    mounted () {
      this.nextUrl = this.properties.routeHome
    },
    methods: {
      getNextUrl (paramName, paramValue) {
        return DestockTools.getNextUrl(this.nextUrl, paramName, paramValue, true)
      },
      gotoNextUrl () {
        DestockTools.goToUrl(this.nextUrl)
      },
      clearSearchResults () {
        this.nextUrl = this.getNextUrl('resultsFor', null)
        this.gotoNextUrl()
      },
      refreshResults (query) {
        if (query !== undefined && query.length >= this.properties.filterMinLengthSearch) {
          this.nextUrl = this.getNextUrl('resultsFor', query)
          this.gotoNextUrl()
        }
      }
    }
  }
</script>
