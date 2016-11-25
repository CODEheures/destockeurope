<template>
    <div class="ui grid">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <template v-else>
            <div class="sixteen wide column">
                <div class="header"><h3><span v-if="advert.isUrgent" class="ui red horizontal label">{{ urgentLabel }}</span>{{ advert.title }}</h3></div>
                <div class="ui image">
                    <template v-for="picture in advert.pictures">
                        <img class="ui top aligned bordered rounded image" :src="picture.url"
                             v-if="picture.isThumb==false && picture.hashName == advert.mainPicture">
                    </template>
                </div>
            </div>
            <div class="sixteen wide column">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <table class="ui very basic celled table">
                            <tbody>
                            <tr>
                                <td class="collapsing">
                                    <i class="cubes icon"></i> {{ totalQuantityLabel }}
                                </td>
                                <td>{{ advert.totalQuantity }}</td>
                            </tr>
                            <tr>
                                <td class="collapsing">
                                    <i class="cube icon"></i> {{ lotMiniQuantityLabel }}
                                </td>
                                <td>{{ advert.lotMiniQuantity }}</td>
                            </tr>
                            <tr>
                                <td class="collapsing">
                                    <i class="money icon"></i> {{ priceLabel }}
                                </td>
                                <td><span class="ui small blue tag label">{{ advert.price }}</span><br/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="sixteen wide column item-description">
                        <div class="description">
                            <p>{{ advert.description }}</p>
                        </div>
                    </div>
                    <div class="sixteen wide right aligned column geodate-computer">
                        <p>
                            <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                            <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                            <i class="unhide icon"></i><span class="meta">{{ advert.views }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            //vue routes
            routeGetAdvert: String,
            //vue vars
            actualLocale: String,
            //vue strings
            totalQuantityLabel: String,
            lotMiniQuantityLabel: String,
            urgentLabel: String,
            priceInfoLabel: String,
            priceLabel: String
        },
        data: () => {
            return {
                advert: {},
                isLoaded: false
            };
        },
        mounted () {
            this.getAdvert();
        },
        methods: {
            getAdvert: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                var that = this;
                this.$http.get(this.routeGetAdvert)
                        .then(
                                function (response) {
                                    that.advert = (response.data).advert;
                                    let breadcrumb = that.advert.breadCrumb;
                                    let lastBread = {
                                        description: [],
                                        id: 0
                                    };
                                    lastBread.description[that.actualLocale] = that.advert.title;
                                    breadcrumb.push(lastBread);
                                    that.$parent.$emit('setBreadCrumb', breadcrumb);
                                    that.isLoaded = true;
                                },
                                function (response) {
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
