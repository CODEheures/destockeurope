<template>
    <div :id="_uid" class="ui fluid search filter">
        <div :class="(!canSearch ? 'ui action left icon input' : 'ui left icon input') + (fluid ? ' fluid' : '')">
            <i class="filter icon"></i>
            <input :class="canSearch==true ? 'prompt' : 'prompt disabled'" type="text" :placeholder="placeHolder" :style="minWidth !== null ? 'min-width:'+minWidth+'px' : ''">
            <button class="ui red icon button" v-if="!canSearch">
                <i class="remove icon"
                    v-on:click="resetSearch(true)">
                </i>
            </button>
        </div>

    </div>
</template>

<script>
  /**
   * Props
   *  - routeSearch: String. Url of the search route
   *  - flagReset: Boolean. A flag to force reset
   *  - resultsFor: String. The value in input when resultFor in url
   *  - update: Boolean. To indicate an update is necessary
   *  - withXsrfToken: Boolean. To add token at the search request
   *  - fields: Object. Fields to show on results: {description:"resume", image:"thumb", price:"price_margin", title:"titleWithManuRef"}
   *  - placeHolder: String. The placeHolder input value
   *
   * Events:
   *  @refreshResults: emit the value of the search. Used for the 'resultFor' param in the next request
   *  @clearSearchResults: emit when the input is cleared
   */
  import Parser from 'url'
  export default {
    props: {
      routeSearch: String,
      flagReset: {
        type: Boolean,
        default: false
      },
      resultsFor: String,
      update: Boolean,
      withXsrfToken: {
        type: Boolean,
        required: false,
        default: false
      },
      fields: Object,
      placeHolder: String,
      fluid: {
        type: Boolean,
        required: false,
        default: true
      },
      minWidth: {
        type: Number,
        required: false,
        default: null
      }
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      }
    },
    watch: {
      flagReset () {
        this.resetSearch(false)
      },
      update () {
        this.updateSearch()
      },
      routeSearch () {
        let that = this
        this.urlForSearch(function (url) {
          that.elemSearch
            .search({
              apiSettings: {
                url: url.replace('query', '{query}'),
                beforeXHR (xhr) {
                  // adjust XHR with additional headers
                  if (that.withXsrfToken === true) {
                    xhr.setRequestHeader('X-XSRF-TOKEN', that.readCookie('XSRF-TOKEN'))
                  }
                }
              },
              // type: 'category',
              fields: that.fields,
              minCharacters: that.properties.filterMinLengthSearch
            })
        })
      }
    },
    data () {
      return {
        dataRouteSearch: '',
        canSearch: true,
        elemSearch: undefined
      }
    },
    mounted () {
      let that = this
      this.elemSearch = $('#' + that._uid)
      this.observeElem(this.elemSearch[0])
    },
    methods: {
      urlForSearch (callback) {
        let urlBase = this.routeSearch
        let parsed = Parser.parse(urlBase, true)
        parsed.search = undefined
        parsed.query.search = 'query'
        this.dataRouteSearch = Parser.format(parsed)
        callback(this.dataRouteSearch)
      },
      resetSearch (withEmit) {
        this.elemSearch.search('set value', '')
        this.elemSearch.search('clear cache')
        this.canSearch = true
        if (withEmit) { this.$emit('clearSearchResults') }
      },
      updateSearch () {
        this.elemSearch = $('#' + this._uid)
        if (this.resultsFor !== undefined && this.resultsFor !== null) {
          this.elemSearch.search('set value', this.resultsFor)
          this.canSearch = false
        }
        else {
          this.resetSearch(false)
        }
      },
      readCookie (name) {
        let match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'))
        return (match ? decodeURIComponent(match[3]) : null)
      },
      observeElem (elem) {
        let MutationObserver = window.MutationObserver || window.WebKitMutationObserver
        let myObserver = new MutationObserver(this.observeMutationHandler)
        let obsConfig = { childList: true, characterData: false, attributes: false, subtree: true }
        myObserver.observe(elem, obsConfig)
      },
      observeMutationHandler (mutationRecords) {
        let that = this
        mutationRecords.forEach(function (mutation) {
          let btnAction = $(mutation.target).find('a.action')
          if (btnAction.length > 0) {
            btnAction.click(function (event) {
              event.preventDefault()
              that.elemSearch.search('hide results')
              let query = that.elemSearch.search('get value')
              that.$emit('refreshResults', query)
              that.canSearch = false
            })
          }
        })
      }
    }
  }
</script>
