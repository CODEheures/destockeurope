<template>
    <div  class="ui grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
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
                    :is-active="masteradsIsActive"
                    :url-img="masteradsUrlImg"
                    :url-redirect="masteradsUrlRedirect"
                    :offset-y-main-container="masteradsOffsetYMainContainer"
                    :selector-main-container="'#ads-offset-y-'+_uid"
                    :width="masteradsWidth"
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
                            @breadCrumbItems="breadCrumbItems"
                            @categoryChoice="categoryChoice"
                    ></advert-filter>
                </div>
            </div>
            <div class="sixteen wide column">
                <h1 class="ui tiny blue header">{{ dataHeader }}</h1>
            </div>
            <div class="sixteen wide tablet thirteen wide computer column">
                <div class="row">
                    <adverts-by-list
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :ads-frequency="parseInt(adsFrenquency)"
                            @bookmarkSuccess="sendToast(strings.bookmarkSuccess, 'success')"
                            @unbookmarkSuccess="sendToast(strings.unbookmarkSuccess, 'success')"
                            @sendToast="sendToast($event.message, $event.type)"
                    ></adverts-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                            :pages="paginate"
                            :route-get-list="''"
                            :fake-page-route="nextUrl"
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
  import _ from 'lodash'
  import { DestockTools } from '../../destockTools'
  import Axios from 'axios'
  export default {
    props: [
      // vue routes
      'routeBookmarkAdd',
      'routeBookmarkRemove',
      'routeNotificationsExistIn',
      'routeNotificationsAdd',
      'routeNotificationsRemove',
      'routeGetHighlight',
      // vue vars
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
        let paginate = _.cloneDeep(this.$store.state.properties['adverts-by-list-item']['list']['adverts'])
        delete paginate.data
        return paginate
      }
    },
    data () {
      return {
        typeMessage: '',
        message: '',
        sendMessage: false,
        isLoaded: true,
        breadcrumbItems: [],
        dataHighlightAdverts: [],
        dataHeader: '',
        nextUrl: ''
      }
    },
    mounted () {
      this.nextUrl = this.getHref()
      this.getHighLightAdvert()
      this.setHeader()
    },
    methods: {
      sendToast: function (message, type) {
        this.typeMessage = type
        this.message = message
        this.sendMessage = !this.sendMessage
      },
      getHighLightAdvert: function () {
        let that = this
        Axios.get(this.routeGetHighlight)
          .then(function (response) {
            that.dataHighlightAdverts = (response.data).adverts
          })
          .catch(function () {
            // that.sendToast(that.strings.loadErrorMessage, 'error')
          })
      },
      setHeader: function () {
        this.dataHeader = this.strings.header
        if (this.breadcrumbItems.length > 0) {
          this.dataHeader = this.dataHeader + ' ' + this.breadcrumbItems[this.breadcrumbItems.length - 1].name
        }
        if (DestockTools.findInUrl('forLocation')) {
          this.dataHeader = this.dataHeader + ' - ' + DestockTools.findInUrl('forLocation')
        }
      },
      getHref: function () {
        return window.location.href
      },
      getNextUrl (paramName, paramValue) {
        return DestockTools.getNextUrl(this.nextUrl, paramName, paramValue, true)
      },
      gotoNextUrl () {
        if (this.nextUrl !== window.location.href) {
          DestockTools.goToUrl(this.nextUrl)
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
      breadCrumbItems (breadcrumbsItems) {
        if (breadcrumbsItems !== undefined && breadcrumbsItems !== null) {
          this.breadcrumbItems = breadcrumbsItems
          this.setHeader()
        }
      },
      categoryChoice (id) {
        if (id !== undefined && id !== null && id >= 0) {
          this.nextUrl = this.getNextUrl('minPrice', null)
          this.nextUrl = this.getNextUrl('maxPrice', null)
          this.nextUrl = this.getNextUrl('minQuantity', null)
          this.nextUrl = this.getNextUrl('maxQuantity', null)
          this.nextUrl = this.getNextUrl('categoryId', id)
          this.gotoNextUrl()
        }
      }
    }
  }
</script>
