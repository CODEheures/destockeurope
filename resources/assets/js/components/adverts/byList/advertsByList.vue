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
                            @updateSuccess="$emit('updateSuccess')"
                            @loadError="$emit('loadError')"
                            @bookmarkSuccess="$emit('bookmarkSuccess')"
                            @unbookmarkSuccess="$emit('unbookmarkSuccess')"
                            @sendToast="$emit('sendToast', $event)"
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
                            @updateSuccess="$emit('updateSuccess')"
                            @loadError="$emit('loadError')"
                            @bookmarkSuccess="$emit('bookmarkSuccess')"
                            @unbookmarkSuccess="$emit('unbookmarkSuccess')"
                            @sendToast="$emit('sendToast', $event)"
                    ></adverts-by-list-item>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            routeBookmarkAdd: String,
            routeBookmarkRemove: String,
            flagForceReload: {
                type: Boolean,
                default: false,
                required: false
            },
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
                return this.$store.state.properties['adverts-by-list-item']['list']['adverts']['data']
            }

        }
    }
</script>
