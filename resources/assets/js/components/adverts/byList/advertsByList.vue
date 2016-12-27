<template>
    <div class="ui segment">
        <div class="ui celled list">
            <div class="ui grid" v-if="routeBookmarkAdd != ''">
                <div class="sixteen wide right aligned column">
                    <span class="ui mini label"><i class="info circle icon"></i>{{ priceInfoLabel }}</span>
                </div>
            </div>
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <template v-if="advertsList.length==0">
                <div class="item ads">
                    <div class="ui info message">
                        <div class="header">{{ noResultFoundHeader }}</div>
                        <p>{{ noResultFoundMessage }}</p>
                    </div>
                </div>
                <div class="item ads">
                    <div class="ui grid">
                        <div class="tablet only computer only row">
                            <div class=" ui centered banner test ad" data-text="banner">
                                <!-- Leaderboard
                                <ins class="adsbygoogle"
                                     style="display:inline-block;width:728px;height:90px"
                                     data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
                                     data-ad-slot="XXXXXXXXXXXXXXXX"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                                !-->
                            </div>
                        </div>
                        <div class="mobile only row">
                            <div class="ui centered half banner test ad" data-text="half banner">
                                <!-- Leaderboard
                                <ins class="adsbygoogle"
                                     style="display:inline-block;width:728px;height:90px"
                                     data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
                                     data-ad-slot="XXXXXXXXXXXXXXXX"></ins>
                                <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                                </script>
                                !-->
                            </div>
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
                            :actual-locale="actualLocale"
                            :total-quantity-label="totalQuantityLabel"
                            :lot-mini-quantity-label="lotMiniQuantityLabel"
                            :urgent-label="urgentLabel"
                            :manage-advert-label="manageAdvertLabel"
                            :renew-advert-label="renewAdvertLabel"
                            :bookmark-info="bookmarkInfo"
                            :views-info="viewsInfo"
                            :price-info-label="priceInfoLabel"
                    ></adverts-by-list-item>
                    <div class="item ads">
                        <div class="ui grid">
                            <div class="tablet only computer only row">
                                <div class=" ui centered banner test ad" data-text="banner">
                                    <!-- Leaderboard
                                    <ins class="adsbygoogle"
                                         style="display:inline-block;width:728px;height:90px"
                                         data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
                                         data-ad-slot="XXXXXXXXXXXXXXXX"></ins>
                                    <script>
                                    (adsbygoogle = window.adsbygoogle || []).push({});
                                    </script>
                                    !-->
                                </div>
                            </div>
                            <div class="mobile only row">
                                <div class="ui centered half banner test ad" data-text="half banner">
                                    <!-- Leaderboard
                                    <ins class="adsbygoogle"
                                         style="display:inline-block;width:728px;height:90px"
                                         data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
                                         data-ad-slot="XXXXXXXXXXXXXXXX"></ins>
                                    <script>
                                    (adsbygoogle = window.adsbygoogle || []).push({});
                                    </script>
                                    !-->
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <adverts-by-list-item
                            :route-bookmark-add="routeBookmarkAdd"
                            :route-bookmark-remove="routeBookmarkRemove"
                            :advert="advert"
                            :actual-locale="actualLocale"
                            :total-quantity-label="totalQuantityLabel"
                            :lot-mini-quantity-label="lotMiniQuantityLabel"
                            :urgent-label="urgentLabel"
                            :manage-advert-label="manageAdvertLabel"
                            :renew-advert-label="renewAdvertLabel"
                            :bookmark-info="bookmarkInfo"
                            :views-info="viewsInfo"
                            :price-info-label="priceInfoLabel"
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
            adsFrequency: Number,
            actualLocale: String,
            totalQuantityLabel: String,
            lotMiniQuantityLabel: String,
            urgentLabel: String,
            priceInfoLabel: String,
            manageAdvertLabel: String,
            renewAdvertLabel: String,
            bookmarkInfo: String,
            viewsInfo: String,
            noResultFoundHeader: String,
            noResultFoundMessage: String
        },
        data: () => {
            return {
                advertsList: [],
                isLoaded: false,
                minPrice: 0,
                maxPrice: 0
            };
        },
        mounted () {
            let that = this;
            this.$watch('routeGetAdvertsList', function () {
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
            this.$on('sendToast', function (message) {
                that.$parent.$emit('sendToast', message);
            });
            this.$on('loadError', function () {
                that.$parent.$emit('loadError');
            });
        },
        methods: {
            getAdvertsList: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                var that = this;
                this.advertsList = [];
                this.$http.get(this.routeGetAdvertsList)
                    .then(
                        function (response) {
                            that.advertsList = (response.data).adverts.data;
                            that.minPrice = parseFloat((response.data).minPrice);
                            that.maxPrice = parseFloat((response.data).maxPrice);
                            that.isLoaded = true;
                            let paginate = response.data.adverts;
                            delete paginate.data;
                            that.$parent.$emit('paginate', paginate);
                        },
                        function (response) {
                            that.$parent.$emit('loadError')
                        }
                    );
            },
        }
    }
</script>
