<template>
    <div :class="canGetDelegations && isPersonnalList && advertsList.length>0 && advertsList[0].is_delegation ? '':'ui segment'">
        <div class="ui celled list">
            <div class="ui grid" v-if="routeBookmarkAdd != ''">
                <div class="sixteen wide right aligned column">
                    <span class="ui mini label"><i class="info circle icon"></i>{{ strings.priceInfoLabel }}</span>
                </div>
            </div>
            <template v-if="advertsList.length==0">
                <div class="item ads">
                    <div class="ui info message">
                        <div class="header">{{ strings.noResultFoundHeader }}</div>
                        <p>{{ strings.noResultFoundMessage }}</p>
                    </div>
                </div>
                <div class="item ads">
                    <div class="ui grid">
                        <div class="tablet only computer only row">
                            <horizontal-468x60></horizontal-468x60>
                        </div>
                        <div class="mobile only row">
                            <horizontal-234x60></horizontal-234x60>
                        </div>
                    </div>
                </div>
            </template>
            <template v-for="(advert, index) in advertsList">
                <template v-if="(index+1)%adsFrequency==0">
                    <adverts-by-list-item
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :advert="advert"
                            :can-get-delegations="canGetDelegations"
                            :is-personnal-list="isPersonnalList"
                            @deleteAdvert="$emit('deleteAdvert', $event)"
                            @unbookmarkSuccess="$emit('unbookmarkSuccess')"
                    ></adverts-by-list-item>
                    <div class="item ads">
                        <div class="ui grid">
                            <div class="tablet only computer only row">
                                <horizontal-468x60></horizontal-468x60>
                            </div>
                            <div class="mobile only row">
                                <horizontal-234x60></horizontal-234x60>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <adverts-by-list-item
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :advert="advert"
                            :can-get-delegations="canGetDelegations"
                            :is-personnal-list="isPersonnalList"
                            @deleteAdvert="$emit('deleteAdvert', $event)"
                            @unbookmarkSuccess="$emit('unbookmarkSuccess')"
                    ></adverts-by-list-item>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
  /**
   * Props
   *  - routeBookmarkAdd: Route to add advert to bookmarks
   *  - routeBookmarkRemove: Route to remove advert of bookmarks
   *  - adsFrequency: Number. The frequency (period to be exact) of ads in advert list
   *  - canGetDelegations: Boolean. If user can get delegation
   *  - isPersonnalList: Boolean. If the list is a personnal list
   *
   * Events:
   *  @unbookmarkSuccess: emit when unbookmark
   *  @deleteAdvert: emit the url to delete advert {'url': this.advert.destroyUrl}
   *
   */
  export default {
    props: {
      routeBookmarkAdd: String,
      routeBookmarkRemove: String,
      adsFrequency: Number,
      canGetDelegations: {
        type: Boolean,
        default: false,
        required: false
      },
      isPersonnalList: {
        type: Boolean,
        default: false,
        required: false
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['adverts-by-list']
      },
      properties () {
        return this.$store.state.properties['global']
      },
      advertsList () {
        return this.$store.state.properties['adverts-by-list']['list']['adverts']['data']
      }
    }
  }
</script>
