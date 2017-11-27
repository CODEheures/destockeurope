<template>
    <div class="ui blue colored segment">
        <div :id="'filter-accordion-'+_uid" class="ui fluid accordion filter">
            <div class="active title">
                <span class="ui blue ribbon label"><i class="dropdown icon"></i><span class="close">{{ strings.ribbonClose }}</span><span class="open">{{ strings.ribbonOpen }}</span></span>
                <breadcrumb
                        :withAction="false"
                        @categoryChoice="$emit('categoryChoice', $event)"
                ></breadcrumb>
            </div>
            <div class="active content">
                <div class="ui grid">
                    <div class="sixteen wide mobile only sixteen wide tablet only column">
                        <categories-select-menu
                                :old-choice="getCurrentCategory()"
                                :with-all="true"
                                :with-redirection-on-click="true"
                                @categoryChoice="$emit('categoryChoice', $event)"
                        ></categories-select-menu>
                    </div>
                </div>
                <div class="ui middle aligned grid">
                    <template v-if="dataCurrenciesList.length > 1">
                        <div class="sixteen wide mobile nine wide computer center aligned column price">
                            <div class="ui grid">
                                <div class="fourteen wide column">
                                    <range-filter
                                            :mini="dataMinPrice"
                                            :maxi="dataMaxPrice"
                                            :handle-min="dataHandleMinPrice"
                                            :handle-max="dataHandleMaxPrice"
                                            :step="0.01"
                                            name="price"
                                            :prefix="dataPricePrefix"
                                            :title="strings.priceTitle"
                                            @rangeUpdate="rangeUpdate"
                                            ref="price1"
                                    ></range-filter>
                                </div>
                                <div class="two wide column">
                                    <currencies-button
                                            :currencies-list="dataCurrenciesList"
                                            :oldCurrency="getCurrentCurrency()"
                                            :withAll="true"
                                            @currencyChoice="$emit('updateFilter', {'currency' : $event})"
                                    ></currencies-button>
                                </div>
                            </div>
                        </div>
                        <div class="sixteen wide mobile seven wide computer center aligned column price">
                            <range-filter
                                    :mini="dataMinQuantity"
                                    :maxi="dataMaxQuantity"
                                    :handle-min="dataHandleMinQuantity"
                                    :handle-max="dataHandleMaxQuantity"
                                    name="quantity"
                                    prefix=""
                                    :title="strings.quantityTitle"
                                    @rangeUpdate="rangeUpdate"
                            ></range-filter>
                        </div>
                    </template>
                    <template v-else>
                        <div class="sixteen wide mobile eight wide computer center aligned column price">
                            <range-filter
                                    :mini="dataMinPrice"
                                    :maxi="dataMaxPrice"
                                    :handle-min="dataHandleMinPrice"
                                    :handle-max="dataHandleMaxPrice"
                                    :step="0.01"
                                    name="price"
                                    :prefix="dataPricePrefix"
                                    :title="strings.priceTitle"
                                    @rangeUpdate="rangeUpdate"
                                    ref="price2"
                            ></range-filter>
                        </div>
                        <div class="sixteen wide mobile eight wide computer center aligned column price">
                            <range-filter
                                    :mini="dataMinQuantity"
                                    :maxi="dataMaxQuantity"
                                    :handle-min="dataHandleMinQuantity"
                                    :handle-max="dataHandleMaxQuantity"
                                    name="quantity"
                                    prefix=""
                                    :title="strings.quantityTitle"
                                    @rangeUpdate="rangeUpdate"
                            ></range-filter>
                        </div>
                    </template>
                </div>
                <div class="ui grid">
                    <div class="doubling two column row">
                        <div class="column">
                            <search-filter
                                    :route-search="routeSearch"
                                    :place-holder="strings.searchPlaceHolder"
                                    :results-for="dataResultsFor"
                                    :update="dataUpdateSearch"
                                    :flag-reset="false"
                                    :fields="{title: 'titleWithManuRef', description : 'resume', image: 'thumb', price: 'price_margin'}"
                                    @clearSearchResults="$emit('clearSearchResults')"
                                    @refreshResults="$emit('refreshResults', $event)"
                            ></search-filter>
                        </div>
                        <div class="column">
                            <location-filter
                                    :accurate-list="locationAccurateList"
                                    @locationUpdate="$emit('updateFilter', $event)"
                                    @clearLocationResults="$emit('clearLocationResults')"
                            ></location-filter>
                        </div>
                    </div>
                </div>
                <div class="ui stackable two column grid">
                    <div class="column">
                        <div class="ui grid">
                            <div class="eight wide column">
                                <div :id="'isUrgent'+_uid" class="ui checkbox filter">
                                    <input type="checkbox" name="isUrgent" v-model="isUrgent">
                                    <label> <span class="ui red horizontal label">{{ strings.urgentLabel }}</span></label>
                                </div>
                            </div>
                            <div class="eight wide column">
                                <div :id="'isNegociated'+_uid" class="ui checkbox filter">
                                    <input type="checkbox" name="isNegociated" v-model="isNegociated">
                                    <label> <span class="ui blue horizontal label">{{ strings.isNegociatedLabel }}</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <notifications-activer
                                :route-exist-in="routeNotificationsExistIn"
                                :route-add="routeNotificationsAdd"
                                :route-remove="routeNotificationsRemove"
                                :topic_id="parseInt(1)"
                        ></notifications-activer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
  /**
   * Props
   *  - routeNotificationsExistIn: String. The route get if notification exist
   *  - routeNotificationsAdd: String. The route to add user in notification
   *  - routeNotificationsRemove: String. The route to del user of notification
   *  - routeSearch: String. The route for search
   *  - locationAccurateList: Array. The list of the accurate geolocation
   *
   * Events:
   *  @categoryChoice: emit the categorie choice value
   *  @updateFilter: emit the object for update filter: {'isUrgent': true}
   *  @clearSearchResults: emit when search input is cleared
   *  @refreshResults: emit when search for: 'a term'
   *  @clearLocationResults: emit when location input is cleared
   */
  import { DestockTools } from '../../destockTools'
  export default {
    props: {
      routeNotificationsExistIn: String,
      routeNotificationsAdd: String,
      routeNotificationsRemove: String,
      routeSearch: String,
      locationAccurateList: Array
    },
    computed: {
      strings () {
        return this.$store.state.strings['advert-filter']
      },
      properties () {
        return this.$store.state.properties['global']
      },
      dataCurrenciesList () {
        return this.$store.state.properties['adverts-by-list']['ranges']['currenciesList']
      },
      dataMinPrice () {
        return parseFloat(this.$store.state.properties['adverts-by-list']['ranges']['minPrice'])
      },
      dataMaxPrice () {
        return parseFloat(this.$store.state.properties['adverts-by-list']['ranges']['maxPrice'])
      },
      dataHandleMinPrice () {
        return parseFloat(DestockTools.findInUrl('minPrice')) || parseFloat(this.$store.state.properties['adverts-by-list']['ranges']['minPrice'])
      },
      dataHandleMaxPrice () {
        return parseFloat(DestockTools.findInUrl('maxPrice')) || parseFloat(this.$store.state.properties['adverts-by-list']['ranges']['maxPrice'])
      },
      dataPricePrefix () {
        return this.$store.state.properties['adverts-by-list']['ranges']['currencySymbol']
      },
      dataMinQuantity () {
        return parseInt(this.$store.state.properties['adverts-by-list']['ranges']['minQuantity'])
      },
      dataMaxQuantity () {
        return parseInt(this.$store.state.properties['adverts-by-list']['ranges']['maxQuantity'])
      },
      dataHandleMinQuantity () {
        return parseInt(DestockTools.findInUrl('minQuantity')) || parseInt(this.$store.state.properties['adverts-by-list']['ranges']['minQuantity'])
      },
      dataHandleMaxQuantity () {
        return parseInt(DestockTools.findInUrl('maxQuantity')) || parseInt(this.$store.state.properties['adverts-by-list']['ranges']['maxQuantity'])
      },
      dataResultsFor () {
        return DestockTools.findInUrl('resultsFor')
      }
    },
    watch: {
      isUrgent () {
        this.$emit('updateFilter', {'isUrgent': this.isUrgent})
      },
      isNegociated () {
        this.$emit('updateFilter', {'isNegociated': this.isNegociated, 'minPrice': 0})
      }
    },
    data () {
      return {
        isUrgent: DestockTools.findInUrl('isUrgent') === 'true' || false,
        isNegociated: DestockTools.findInUrl('isNegociated') === 'true' || false,
        dataUpdateSearch: false
      }
    },
    mounted () {
      let that = this
      // isUrgent
      let isUrgent = $('#isUrgent' + this._uid)
      isUrgent.checkbox({
        onChecked () { that.isUrgent = true },
        onUnchecked () { that.isUrgent = false }
      })
      // isNegociated
      let isNegociated = $('#isNegociated' + this._uid)
      isNegociated.checkbox({
        onChecked () { that.isNegociated = true },
        onUnchecked () { that.isNegociated = false }
      })
      // search filter
      this.dataUpdateSearch = !this.dataUpdateSearch
      // Accordion
      let accordionElement = $('#filter-accordion-' + this._uid)
      if ($(window).width() < 768) {
        accordionElement.accordion('close', 0)
      }
      else {
        accordionElement.accordion()
      }
    },
    methods: {
      getCurrentCategory () {
        let categoryId = DestockTools.findInUrl('categoryId')
        // eslint-disable-next-line eqeqeq
        if (categoryId !== null && categoryId == parseInt(categoryId) && categoryId > 0) {
          return parseInt(DestockTools.findInUrl('categoryId'))
        }
        else {
          return 0
        }
      },
      getCurrentCurrency () {
        return DestockTools.findInUrl('currency')
      },
      rangeUpdate (event) {
        if (event.name === 'price') {
          this.$emit('updateFilter', {'minPrice': event.values[0], 'maxPrice': event.values[1]})
        }
        if (event.name === 'quantity') {
          this.$emit('updateFilter', {'minQuantity': event.values[0], 'maxQuantity': event.values[1]})
        }
      }
    }
  }
</script>
