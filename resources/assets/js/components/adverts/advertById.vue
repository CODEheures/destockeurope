<template>
    <div class="ui grid">
        <div :id="'modal-'+_uid" class="ui modal">
            <i class="close icon"></i>
            <div class="image content">
                <img class="image lightbox" :src="dataLightBoxUrl" :style="'height: ' + dataHeight + 'px;'">
            </div>
        </div>
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <template v-else>
            <div class="sixteen wide column">
                <swiper-gallerie
                        :pictures="advert.pictures"
                        :image-ratio="imageRatio"
                ></swiper-gallerie>
            </div>
            <div class="sixteen wide column">
                <div class="ui grid">
                    <div class="sixteen wide right aligned column geodate-computer">
                        <p>
                            <i class="map signs icon"></i><span class="meta">{{ advert.geoloc }}</span>
                            <i class="calendar icon"></i><span class="meta">{{ getMoment(advert.updated_at) }}</span>
                            <i class="unhide icon"></i><span class="meta">{{ advert.views }}</span>
                        </p>
                    </div>
                    <div class="sixteen wide column">
                        <table id="table-advert-infos" class="ui very basic celled table advert-infos">
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
            imageRatio: Number,
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
                isLoaded: false,
                dataHashName: '',
                dataLightBoxUrl: '',
                dataHeight: ''
            };
        },
        mounted () {
            this.getAdvert();
            this.$on('openLightBox', function (imgUrl) {
                this.openLightBox(imgUrl);
            });
            this.dataHeight = $('#modal-'+this._uid).width()/this.imageRatio;
        },
        methods: {
            getAdvert: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                this.$http.get(this.routeGetAdvert)
                        .then(
                                function (response) {
                                    that.advert = (response.data).advert;
                                    that.dataHashName = that.advert.mainPicture;
                                    let breadcrumb = that.advert.breadCrumb;
                                    let lastBread = {
                                        description: [],
                                        id: 0
                                    };
                                    lastBread.description[that.actualLocale] = that.advert.title;
                                    breadcrumb.push(lastBread);
                                    that.$parent.$emit('setBreadCrumb', breadcrumb);
                                    that.$parent.$emit('setHeader', {'id': that.advert.id, 'isUrgent': that.advert.isUrgent, 'title': that.advert.title});
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
            },
            openLightBox: function (imgUrl) {
                this.dataLightBoxUrl = imgUrl;
                this.dataHeight = $('.lightBox').width/this.imageRatio;
                $('#modal-'+this._uid).modal({
                    closable: true,
                    blurring: true
                }).modal('show');
            }
        }
    }
</script>
