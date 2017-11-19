<template>
    <div class="ui blue colored segment">
        <div :id="'filter-accordion-'+_uid" class="ui fluid accordion filter">
            <div class="active title">
                <span class="ui blue ribbon label"><i class="dropdown icon"></i><span class="close">{{ strings.ribbonClose }}</span><span class="open">{{ strings.ribbonOpen }}</span></span>
            </div>
            <div class="active content">
                <div class="ui grid">
                    <div class="column">
                        <search-filter
                                :route-search="routeSearch"
                                :place-holder="strings.placeHolder"
                                :results-for="dataResultsFor"
                                :update="dataUpdateSearch"
                                :flag-reset="false"
                                :with-xsrf-token="true"
                                :fields="{title: 'titleWithManuRef', description : 'resume', image: 'thumb', price: 'price_margin'}"
                                @clearSearchResults="$emit('clearSearchResults')"
                                @refreshResults="$emit('refreshResults', $event)"
                        ></search-filter>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
  import { DestockTools } from '../../destockTools'
  export default {
    props: {
      routeSearch: {
        type: String
      }
    },
    data () {
      return {
        strings: {},
        properties: {},
        dataResultsFor: '',
        dataUpdateSearch: false
      }
    },
    mounted () {
      this.strings = this.$store.state.strings['advert-simple-search-filter']
      this.properties = this.$store.state.properties['global']
      // search filter
      this.dataResultsFor = DestockTools.findInUrl('resultsFor')
      this.dataUpdateSearch = !this.dataUpdateSearch
      // Accordion
      let accordionElement = $('#filter-accordion-' + this._uid)
      if ($(window).width() < 768) {
        accordionElement.accordion('close', 0)
      }
      else {
        accordionElement.accordion()
      }
    }
  }
</script>
