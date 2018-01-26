<template>
    <div  class="ui grid">
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
        <div class="tablet only computer only sixteen wide column categories-horizontal-menu">
            <div class="row">
                <categories-horizontal-menu
                    @categoryChoice="categoryChoice"
                ></categories-horizontal-menu>
            </div>
        </div>
        <div class="sixteen wide column" v-if="masteradsIsActive=='1'">
            <masterads
                    :route-image-server = "masteradsRouteImageServer"
                    :is-active="masteradsIsActive==='1'"
                    :url-img="masteradsUrlImg"
                    :url-redirect="masteradsUrlRedirect"
                    :offset-y-main-container="masteradsOffsetYMainContainer"
                    :selector-main-container="'#ads-offset-y-'+_uid"
                    :width="parseInt(masteradsWidth)"
                    :ads-offset-y="-10"
            ></masterads>
        </div>
        <div class="row" :id="'ads-offset-y-'+_uid">
            <div class="sixteen wide column">
                <div class="row filters">
                    <advert-filter
                            :route-notifications-exist-in="routeNotificationsExistIn"
                            :route-notifications-add="routeNotificationsAdd"
                            :route-notifications-remove="routeNotificationsRemove"
                            :route-search="nextUrl"
                            :location-accurate-list="dataLocationAccurateList"
                            @updateFilter="updateFilter"
                            @clearLocationResults="clearLocationResults"
                            @clearSearchResults="clearSearchResults"
                            @refreshResults="refreshResults"
                            @categoryChoice="categoryChoice"
                    ></advert-filter>
                </div>
            </div>
            <div class="sixteen wide tablet thirteen wide computer column" style="display: flex; flex-flow: row nowrap; justify-content: space-between; align-items: center; margin-bottom: 0.3rem;">
                <h1 class="ui tiny blue header" style="margin-bottom: 0">{{ dataHeader }}</h1>
                <div class="fb-share-button"
                     :data-href="properties.routeFacebookSharer"
                     data-layout="button"
                     data-size="large"
                ></div>
            </div>
            <div class="sixteen wide tablet thirteen wide computer column">
                <div class="row">
                    <adverts-by-list
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :ads-frequency="parseInt(adsFrenquency)"
                    ></adverts-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                            :pages="paginate"
                            :fake-page-route="nextUrl"
                            :per-page-option="true"
                            @changePage="changePage"
                        ></pagination>
                    </div>
                </div>
            </div>
            <div class="computer only three wide column">
                <div class="ui centered grid">
                    <template v-if="dataHighlightAdverts.length > 1">
                        <div class="sixteen wide column" v-for="highLightAdvert in dataHighlightAdverts">
                            <advert-highlight
                                    :advert="highLightAdvert"
                            ></advert-highlight>
                        </div>
                    </template>

                    <template v-else>
                        <div class="sixteen wide column">
                            <advert-highlight v-if="dataHighlightAdverts.length == 1"
                                    :advert="dataHighlightAdverts[0]"
                            ></advert-highlight>
                            <advert-highlight
                                    :advert="dataFakeHighlightAdvert"
                            ></advert-highlight>
                        </div>
                    </template>
                    <div class="sixteen wide column">
                        <vertical-160x600
                            :centered="true"
                        ></vertical-160x600>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
  /**
   * Props
   *  - routeBookmarkAdd: String. The route to add advert on user bookmark
   *  - routeBookmarkRemove: String. The route to remove advert of user bookmark
   *  - routeNotificationsExistIn: String. The route to get if user exist in notification
   *  - routeNotificationsAdd: String. The route to add user in notification
   *  - routeNotificationsRemove: String. The route to remove user of notification
   *  - routeGetHighlight: String. The route to get highlights adverts
   *  - masteradsRouteImageServer: String. The route to get image cross site
   *  - masteradsIsActive: String. The boolean status of master ads
   *  - masteradsUrlImg: String. The url of master img
   *  - masteradsUrlRedirect: String. The url of master ads anchor
   *  - masteradsOffsetYMainContainer: String. Offset of the container for master ads visibility
   *  - masteradsWidth: String. The width of the master ads
   *  - filterLocationAccurateList: String. JSON of the accurate possible geoloc
   *  - adsFrenquency: String. The frequency of ads (period to be exact)
   *  - fakeHighlightAdvert: String. Json of a fake advert for promotionnal hightlight
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
      'routeNotificationsExistIn',
      'routeNotificationsAdd',
      'routeNotificationsRemove',
      'routeGetHighlight',
      'masteradsRouteImageServer',
      'masteradsIsActive',
      'masteradsUrlImg',
      'masteradsUrlRedirect',
      'masteradsOffsetYMainContainer',
      'masteradsWidth',
      'filterLocationAccurateList',
      'adsFrenquency',
      'fakeHighlightAdvert'
    ],
    computed: {
      strings () {
        return this.$store.state.strings['welcome1']
      },
      properties () {
        return this.$store.state.properties['global']
      },
      dataLocationAccurateList () {
        return JSON.parse(this.filterLocationAccurateList)
      },
      dataFakeHighlightAdvert () {
        return JSON.parse(this.fakeHighlightAdvert)
      },
      paginate () {
        let paginate = _.cloneDeep(this.$store.state.properties['adverts-by-list']['list']['adverts'])
        delete paginate.data
        return paginate
      },
      dataHeader () {
        let header = this.strings.header
        if ('breadcrumbItems' in this.$store.state.properties && this.$store.state.properties.breadcrumbItems.length > 0) {
          header = header + ' ' + this.$store.state.properties.breadcrumbItems[this.$store.state.properties.breadcrumbItems.length - 1].name
        }
        if (this.forLocation) {
          header = header + ' - ' + this.forLocation
        }
        return header
      }
    },
    data () {
      return {
        typeMessage: '',
        message: '',
        sendMessage: false,
        isLoaded: true,
        dataHighlightAdverts: [],
        nextUrl: '',
        forLocation: DestockTools.findInUrl('forLocation')
      }
    },
    mounted () {
      this.nextUrl = this.getHref()
      this.getHighLightAdvert()
      DestockTools.setBreadCrumbItems(this.$store, null, this.strings.allLabel)
      window.onpopstate = () => {
        this.goBack()
      }
    },
    methods: {
      getHighLightAdvert () {
        let that = this
        Axios.get(this.routeGetHighlight)
          .then(function (response) {
            that.dataHighlightAdverts = (response.data).adverts
          })
          .catch(function () {
          })
      },
      getHref () {
        return window.location.href
      },
      getNextUrl (paramName, paramValue) {
        return DestockTools.getNextUrl(this.nextUrl, paramName, paramValue, true)
      },
      gotoNextUrl () {
        let that = this
        if (this.nextUrl !== window.location.href) {
          DestockTools.smoothscroll()
          Axios.get(this.nextUrl, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
            .then(function (response) {
              that.$store.commit('setProperties', {name: 'adverts-by-list', properties: response.data})
              history.pushState({byXhr: true}, '', that.nextUrl)
              that.forLocation = DestockTools.findInUrl('forLocation')
            })
            .catch(function () {
              that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
            })
          // DestockTools.goToUrl(this.nextUrl)
        }
      },
      updateFilter (result) {
        Object.keys(result).forEach((key) => {
          this.nextUrl = this.getNextUrl(key, result[key])
        })
        this.gotoNextUrl()
      },
      clearLocationResults () {
        this.dataLocationAccurateList.forEach((key) => {
          this.nextUrl = this.getNextUrl(key, null)
        })
        this.nextUrl = this.getNextUrl('forLocation', null)
        this.gotoNextUrl()
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
      categoryChoice (id) {
        DestockTools.setBreadCrumbItems(this.$store, id, this.strings.allLabel)
        if (id !== undefined && id !== null && id >= 0) {
          this.nextUrl = this.getNextUrl('minPrice', null)
          this.nextUrl = this.getNextUrl('maxPrice', null)
          this.nextUrl = this.getNextUrl('minQuantity', null)
          this.nextUrl = this.getNextUrl('maxQuantity', null)
          this.nextUrl = this.getNextUrl('categoryId', id)
          this.gotoNextUrl()
        }
      },
      goBack () {
        window.location.reload()
      }
    }
  }
</script>
