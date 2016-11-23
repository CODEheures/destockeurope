<template>
    <div>
        <div class="ui celled list">
            <div class="ui grid">
                <div class="sixteen wide right aligned column">
                    <span class="ui mini label"><i class="info circle icon"></i>Tous les prix sont unitaires</span>
                </div>
            </div>
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <template v-if="advertsList.length==0">
                <div class="item advert">
                    <div class="ui info message">
                        <div class="header">{{ noResultFoundHeader }}</div>
                        <p>{{ noResultFoundMessage }}</p>
                    </div>
                </div>
                <div class="item advert">
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
                    <a :href="advert.url"  class="item advert">
                        <div class="ui grid">
                            <div class="six wide aligned mobile four wide tablet four wide computer column">
                                <div class="ui image">
                                    <div class="ui right blue corner label"><i class="icon">{{ advert.pictures.length/2 }}</i></div>
                                    <img  class="ui top aligned medium bordered rounded image" :src="advert.thumb">
                                </div>
                            </div>
                            <div class="twelve wide tablet only twelve wide computer only column">
                                <div class="ui grid">
                                    <div class="ten wide left aligned column">
                                        <div class="header"><h3>{{ advert.title }}</h3></div>
                                        <p>
                                        <span class="ui breadcrumb">
                                            <template v-for="(item,index) in advert.breadCrumb">
                                                <div class="active section">{{ item.description[actualLocale] }}</div>
                                                <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                            </template>
                                        </span>
                                        </p>
                                    </div>
                                    <div class="six wide right aligned vertical middle aligned column">
                                        <p class="price">
                                            <span class="ui small blue tag label">{{ advert.price }}</span><br />
                                            <span :title="totalQuantityLabel"><i class="cubes icon"></i>{{ advert.totalQuantity }} </span>
                                            <span :title="lotMiniQuantityLabel"><i class="cube icon"></i>{{ advert.lotMiniQuantity }}</span>
                                            <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                        </p>
                                    </div>
                                    <div class="sixteen wide column item-description">
                                        <div class="description">
                                            <p>{{ advert.resume }}</p>
                                        </div>
                                    </div>
                                    <div class="sixteen wide right aligned column geodate-computer">
                                        <p>
                                            <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                                            <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="ten wide mobile only column">
                                <span class="ui mini breadcrumb">
                                    <template v-for="(item,index) in advert.breadCrumb">
                                        <div class="active section">{{ item.description[actualLocale] }}</div>
                                        <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                    </template>
                                </span>
                                <div class="header"><h3>{{ advert.title }}</h3></div>
                                <div class="ui grid">
                                    <div class="sixteen wide mobile only right aligned column">
                                        <p class="price">
                                            <span class="ui small blue tag label">{{ advert.price }}</span><br />
                                            <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                            <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                            <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="sixteen wide mobile only column">
                                <div class="description">
                                    <p>{{ advert.resume }}</p>
                                </div>
                            </div>
                            <div class="sixteen wide right aligned mobile only column geodate-mobile">
                                <p>
                                    <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                                    <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                                </p>
                            </div>
                        </div>
                    </a>
                    <div class="item advert">
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
                    <a :href="advert.url"  class="item advert">
                        <div class="ui grid">
                            <div class="six wide aligned mobile four wide tablet four wide computer column">
                                <div class="ui image">
                                    <div class="ui right blue corner label"><i class="icon">{{ advert.pictures.length/2 }}</i></div>
                                    <img  class="ui top aligned medium bordered rounded image" :src="advert.thumb">
                                </div>
                            </div>
                            <div class="twelve wide tablet only twelve wide computer only column">
                                <div class="ui grid">
                                    <div class="ten wide left aligned column">
                                        <div class="header"><h3>{{ advert.title }}</h3></div>
                                        <p>
                                        <span class="ui breadcrumb">
                                            <template v-for="(item,index) in advert.breadCrumb">
                                                <div class="active section">{{ item.description[actualLocale] }}</div>
                                                <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                            </template>
                                        </span>
                                        </p>
                                    </div>
                                    <div class="six wide right aligned vertical middle aligned column">
                                        <p class="price">
                                            <span class="ui small blue tag label">{{ advert.price }}</span><br />
                                            <span :title="totalQuantityLabel"><i class="cubes icon"></i>{{ advert.totalQuantity }} </span>
                                            <span :title="lotMiniQuantityLabel"><i class="cube icon"></i>{{ advert.lotMiniQuantity }}</span>
                                            <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                        </p>
                                    </div>
                                    <div class="sixteen wide column item-description">
                                        <div class="description">
                                            <p>{{ advert.resume }}</p>
                                        </div>
                                    </div>
                                    <div class="sixteen wide right aligned column geodate-computer">
                                        <p>
                                            <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                                            <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="ten wide mobile only column">
                                <span class="ui mini breadcrumb">
                                    <template v-for="(item,index) in advert.breadCrumb">
                                        <div class="active section">{{ item.description[actualLocale] }}</div>
                                        <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                    </template>
                                </span>
                                <div class="header"><h3>{{ advert.title }}</h3></div>
                                <div class="ui grid">
                                    <div class="sixteen wide mobile only right aligned column">
                                        <p class="price">
                                            <span class="ui small blue tag label">{{ advert.price }}</span><br />
                                            <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                            <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                            <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="sixteen wide mobile only column">
                                <div class="description">
                                    <p>{{ advert.resume }}</p>
                                </div>
                            </div>
                            <div class="sixteen wide right aligned mobile only column geodate-mobile">
                                <p>
                                    <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                                    <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                                </p>
                            </div>
                        </div>
                    </a>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            routeGetAdvertsList: String,
            adsFrequency: Number,
            actualLocale: String,
            totalQuantityLabel: String,
            lotMiniQuantityLabel: String,
            urgentLabel: String,
            priceInfoLabel: String,
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
            this.$watch('routeGetAdvertsList', function () {
                this.getAdvertsList();
            });
            this.$watch('minPrice', function () {
                this.$parent.$emit('setRangePrice', {'mini': this.minPrice, 'maxi': this.maxPrice});
            });
            this.$watch('maxPrice', function () {
                this.$parent.$emit('setRangePrice', {'mini': this.minPrice, 'maxi': this.maxPrice});
            })
        },
        methods: {
            getAdvertsList: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                var that = this;
                this.advertsList = [];
                this.$http.get(this.routeGetAdvertsList)
                        .then(
                                function (response)  {
                                    that.advertsList = (response.data).adverts.data;
                                    that.minPrice = parseFloat((response.data).minPrice);
                                    that.maxPrice = parseFloat((response.data).maxPrice);
                                    that.isLoaded = true;
                                    let paginate = response.data.adverts;
                                    delete paginate.data;
                                    that.$parent.$emit('paginate', paginate);
                                },
                                function (response)  {
                                    that.$parent.$emit('loadError')
                                }
                        );
            },
            getMoment: function (dateTime) {
                moment.locale(this.actualLocale);
                return moment(dateTime).fromNow()
            }
        }
    }
</script>
