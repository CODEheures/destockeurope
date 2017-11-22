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
                                :update="dataUpdate"
                                :flag-reset="flagResetSearch"
                                :with-xsrf-token="true"
                                :fields="{title: 'email', description : 'created_at'}"
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
  /**
   * Props
   *  - update: Boolean. A flag to update the search filter
   *  - filter: Object. {resultFor: 'a term'} to set searchFilter resultFor
   *  - routeSearch: String. The route to get results of search
   *  - flagResetSearch: Boolean. To force search filter to clear
   *
   * Events:
   *  @clearSearchResults: emit when search input is cleared
   *  @refreshResults: emit when search for: 'a term'
   *
   */
  export default {
    props: {
      update: Boolean,
      filter: Object,
      routeSearch: String,
      flagResetSearch: Boolean
    },
    watch: {
      update () {
        this.dataResultsFor = this.filter.resultsFor
        this.dataUpdate = !this.dataUpdate
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['user-filter']
      }
    },
    data () {
      return {
        dataResultsFor: '',
        dataUpdate: false
      }
    },
    mounted () {
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
