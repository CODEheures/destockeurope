<template>
    <div>
        <div class="ui celled list">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <template v-for="(advert, index) in advertsList">
                <template v-if="(index+1)%adsFrequency==0">
                    <a :href="routeGetAdvertsList+'/'+advert.id"  class="item advert">
                        <div class="ui grid">
                            <div class="sixteen wide center aligned mobile four wide tablet four wide computer column">
                                <div class="ui image">
                                    <div class="ui right blue corner label"><i class="icon">{{ advert.pictures.length/2 }}</i></div>
                                    <img  class="ui top aligned medium bordered rounded image" :src="routeGetThumb+'/'+advert.mainPicture+'/'+advert.id">
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
                                            <span class="ui large blue tag label">{{ advert.price }} {{ advert.currency }}</span><br />
                                            <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                            <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                            <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                        </p>
                                    </div>
                                    <div class="sixteen wide column">
                                        <div class="description">
                                            <p>{{ advert.description | format }}</p>
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
                            <div class="sixteen wide mobile only column">
                        <span class="ui breadcrumb">
                                <template v-for="(item,index) in advert.breadCrumb">
                                    <div class="active section">{{ item.description[actualLocale] }}</div>
                                    <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                </template>
                            </span>
                                <div class="header"><h3>{{ advert.title }}</h3></div>
                            </div>
                            <div class="sixteen wide mobile only right aligned column">
                                <p class="price">
                                    <span class="ui large blue tag label">{{ advert.price }} {{ advert.currency }}</span><br />
                                    <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                    <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                    <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                </p>
                            </div>
                            <div class="sixteen wide mobile only column">
                                <div class="description">
                                    <p>{{ advert.description | format }}</p>
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
                        <div class="ui centered  banner test ad" data-text="banner">
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
                </template>
                <template v-else>
                    <a :href="routeGetAdvertsList+'/'+advert.id"  class="item advert">
                        <div class="ui grid">
                            <div class="sixteen wide center aligned mobile four wide tablet four wide computer column">
                                <div class="ui image">
                                    <div class="ui right blue corner label"><i class="icon">{{ advert.pictures.length/2 }}</i></div>
                                    <img  class="ui top aligned medium bordered rounded image" :src="routeGetThumb+'/'+advert.mainPicture+'/'+advert.id">
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
                                            <span class="ui large blue tag label">{{ advert.price }} {{ advert.currency }}</span><br />
                                            <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                            <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                            <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                        </p>
                                    </div>
                                    <div class="sixteen wide column">
                                        <div class="description">
                                            <p>{{ advert.description | format }}</p>
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
                            <div class="sixteen wide mobile only column">
                        <span class="ui breadcrumb">
                                <template v-for="(item,index) in advert.breadCrumb">
                                    <div class="active section">{{ item.description[actualLocale] }}</div>
                                    <i class="right angle icon divider" v-if="index != advert.breadCrumb.length-1"></i>
                                </template>
                            </span>
                                <div class="header"><h3>{{ advert.title }}</h3></div>
                            </div>
                            <div class="sixteen wide mobile only right aligned column">
                                <p class="price">
                                    <span class="ui large blue tag label">{{ advert.price }} {{ advert.currency }}</span><br />
                                    <span><i class="cubes icon" :title="totalQuantityLabel"></i>{{ advert.totalQuantity }} </span>
                                    <span><i class="cube icon" :title="lotMiniQuantityLabel"></i>{{ advert.lotMiniQuantity }}</span>
                                    <span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>
                                </p>
                            </div>
                            <div class="sixteen wide mobile only column">
                                <div class="description">
                                    <p>{{ advert.description | format }}</p>
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
            routeGetThumb: String,
            adsFrequency: Number,
            actualLocale: String,
            totalQuantityLabel: String,
            lotMiniQuantityLabel: String,
            urgentLabel: String
        },
        data: () => {
            return {
                advertsList: [],
                isLoaded: false
            };
        },
        mounted () {
            this.$watch('routeGetAdvertsList', function () {
                this.getAdvertsList();
            });
        },
        filters: {
            format (description) {
                let first = description.charAt(0);
                let resume = description.substr(1,200);
                return first.toUpperCase()+resume+'...';
            }
        },
        methods: {
            getAdvertsList: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                var that = this;
                this.$http.get(this.routeGetAdvertsList)
                        .then(
                                function (response)  {
                                    that.advertsList = (response.data).data;
                                    that.isLoaded = true;
                                    let paginate = response.data;
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
