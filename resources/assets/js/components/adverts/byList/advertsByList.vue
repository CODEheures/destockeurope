<template>
    <div class="ui segment">
        <div class="ui celled list">
            <div class="ui grid" v-if="routeBookmarkAdd != ''">
                <div class="sixteen wide right aligned column">
                    <span class="ui mini label"><i class="info circle icon"></i>{{ strings.priceInfoLabel }}</span>
                </div>
            </div>
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <template v-if="advertsList.length==0">
                <div class="item ads">
                    <div class="ui info message">
                        <div class="header">{{ strings.noResultFoundHeader }}</div>
                        <p>{{ strings.noResultFoundMessage }}</p>
                    </div>
                </div>
                <div v-if="isLoaded"  class="item ads">
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
                    ></adverts-by-list-item>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            routeGetAdvertsList: String,
            routeBookmarkAdd: String,
            routeBookmarkRemove: String,
            reloadOnUnbookmarkSuccess: {
                type: Boolean,
                default: false,
                required: false
            },
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
        data: () => {
            return {
                strings: {},
                properties: {},
                advertsList: [],
                isLoaded: false,
                minPrice: 0,
                maxPrice: 0
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['adverts-by-list'];
            this.properties = this.$store.state.properties['global'];
            let that = this;
            this.$watch('routeGetAdvertsList', function () {
                this.getAdvertsList();
            });
            this.$watch('flagForceReload', function () {
                this.getAdvertsList();
            });
            this.$watch('minPrice', function () {
                this.$parent.$emit('setRangePrice', {'mini': this.minPrice, 'maxi': this.maxPrice});
            });
            this.$watch('maxPrice', function () {
                this.$parent.$emit('setRangePrice', {'mini': this.minPrice, 'maxi': this.maxPrice});
            });
            this.$on('bookmarkSuccess', function () {
                that.$parent.$emit('bookmarkSuccess');
            });
            this.$on('unbookmarkSuccess', function () {
                that.$parent.$emit('unbookmarkSuccess');
                if(that.reloadOnUnbookmarkSuccess==true) {
                    that.getAdvertsList(true);
                }
            });
            this.$on('deleteAdvert', function (event) {
               that.$parent.$emit('deleteAdvert', event);
            });
            this.$on('sendToast', function (message) {
                that.$parent.$emit('sendToast', message);
            });
            this.$on('loadError', function () {
                that.$parent.$emit('loadError');
            });
            this.$on('updateSuccess', function () {
                that.$parent.$emit('updateSuccess');
            });
        },
        methods: {
            getAdvertsList: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                this.advertsList = [];
                axios.get(this.routeGetAdvertsList)
                    .then(function (response) {
                        that.advertsList = (response.data).adverts.data;
                        that.minPrice = parseFloat((response.data).minPrice);
                        that.maxPrice = parseFloat((response.data).maxPrice);
                        that.isLoaded = true;
                        let paginate = response.data.adverts;
                        delete paginate.data;
                        that.$parent.$emit('paginate', paginate);
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError')
                    });
            }
        }
    }
</script>
