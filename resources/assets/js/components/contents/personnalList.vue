<template>
    <div  class="ui one column grid">
        <div :id="'modal2-'+_uid" class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ strings.modalValidHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="legal icon"></i>
                </div>
                <div class="description">
                    <p>{{ strings.modalValidDescription }}</p>
                </div>
            </div>
            <div class="actions">
                <div class="two fluid ui inverted buttons">
                    <div class="ui cancel red basic inverted button">
                        <i class="remove icon"></i>
                        {{ strings.modalNo }}
                    </div>
                    <div class="ui ok green basic inverted button">
                        <i class="checkmark icon"></i>
                        {{ strings.modalYes }}
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="row">
            <div v-if="isDelegation" class="sixteen wide column">
                <div class="row filters">
                    <advert-simple-search-filter
                            :route-search="nextUrl"
                            @clearSearchResults="clearSearchResults"
                            @refreshResults="refreshResults"
                    ></advert-simple-search-filter>
                </div>
            </div>
            <div :class="isDelegation ? 'sixteen wide column' : 'sixteen wide tablet twelve wide computer column'">
                <div class="row">
                    <adverts-by-list
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :ads-frequency="parseInt(adsFrequency)"
                            :can-get-delegations="canGetDelegations==true"
                            :is-personnal-list="isPersonnalList==true"
                            @deleteAdvert="destroyMe($event.url)"
                            @unbookmarkSuccess="unbookmarkSuccess"
                    ></adverts-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                            :pages="paginate"
                            :fake-page-route="nextUrl"
                            @changePage="changePage"
                        ></pagination>
                    </div>
                </div>
            </div>
            <div v-if="!isDelegation" id="welcome-ads" class="computer only four wide column">
                <div>
                    <div class="sixteen right aligned column">
                        <vertical-160x600></vertical-160x600>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  /**
   * Props
   *  - routeBookmarkAdd: String. The route to add advert on user bookmarks
   *  - routeBookmarkRemove: String. The route to remove advert of user bookmarks
   *  - reloadAdvertOnUnbookmarkSuccess: String. Boolean status if want to reload on unbookmark
   *  - adsFrequency: String. The frequency of ads on list (period to be exact)
   *  - canGetDelegations: String. Boolean status if the user can get delegation
   *  - isPersonnalList: String. Boolean status if list is personnal
   *  - isDelegation: String. Boolean status if list is delegation list
   *  - contentHeader: String. The header
   *
   * Events:
   *
   */
  import _ from 'lodash'
  import { DestockTools } from '../../destockTools'
  import Axios from 'axios'
  export default {
    props: [
      'routeBookmarkAdd',
      'routeBookmarkRemove',
      'reloadAdvertOnUnbookmarkSuccess',
      'adsFrequency',
      'canGetDelegations',
      'isPersonnalList',
      'isDelegation',
      'contentHeader'
    ],
    computed: {
      strings () {
        return this.$store.state.strings['personnal-list']
      },
      properties () {
        return this.$store.state.properties['global']
      },
      paginate () {
        let paginate = _.cloneDeep(this.$store.state.properties['adverts-by-list']['list']['adverts'])
        delete paginate.data
        return paginate
      }
    },
    data () {
      return {
        typeMessage: '',
        message: '',
        sendMessage: false,
        nextUrl: ''
      }
    },
    mounted () {
      this.nextUrl = this.getHref()
      sessionStorage.clear()
    },
    methods: {
      destroyMe (url) {
        let modalForm = $('#modal2-' + this._uid)
        let that = this
        modalForm.modal({
          closable: true,
          blurring: false,
          onApprove () {
            Axios.delete(url)
              .then(function (response) {
                that.gotoNextUrl(true)
              })
              .catch(function (error) {
                if (error.response && error.response.status === 409) {
                  that.$alertV({'message': error.response.data, 'type': 'error'})
                }
                else {
                  that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
                }
              })
          }
        }).modal('show')
      },
      getHref () {
        return window.location.href
      },
      getNextUrl (paramName, paramValue) {
        return DestockTools.getNextUrl(this.nextUrl, paramName, paramValue, true)
      },
      gotoNextUrl (forceLoad = false) {
        if (this.nextUrl !== window.location.href || forceLoad === true) {
          DestockTools.goToUrl(this.nextUrl)
        }
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
      },
      changePage (url) {
        this.nextUrl = url
        this.gotoNextUrl()
      },
      unbookmarkSuccess () {
        this.nextUrl = this.getNextUrl('page', null)
        this.gotoNextUrl(true)
      }
    }
  }
</script>
