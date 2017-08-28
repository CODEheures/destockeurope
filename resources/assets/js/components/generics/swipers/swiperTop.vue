<template>
    <div class="swiper-container">
        <div class="swiper-pagination"></div>
        <div class="swiper-wrapper">
            <div class="swiper-slide" v-if="videoId != null && videoId != ''">
                <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + videoId" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
            <template v-if="dataPictures.length > 0">
                <div class="swiper-slide" v-for="picture in dataPictures">
                    <template v-if="!lazyLoad">
                        <img :src="picture.normalUrl" v-on:click="lightBoxMe(picture)">
                    </template>
                    <template v-else>
                        <img :data-src="picture.normalUrl" class="swiper-lazy" v-on:click="lightBoxMe(picture)">
                        <div class="swiper-lazy-preloader swiper-lazy-preloader-black"></div>
                    </template>
                </div>
            </template>
            <template v-else>
                <div class="swiper-slide">
                    <div style="height:100%;display: flex;justify-content: center;align-items: center">
                        <h1 class="ui icon header">
                            <i class="file image outline icon"></i>
                            <div class="content">
                                {{ strings.firstHeader }}
                                <div class="sub header">{{ strings.firstDescription }}</div>
                            </div>
                        </h1>
                    </div>
                </div>
            </template>
        </div>
        <div class="swiper-button-next"><i class="huge chevron right icon"></i></div>
        <div class="swiper-button-prev"><i class="huge chevron left icon"></i></div>
    </div>
</template>

<script>
    export default {
        props: {
            options: {
                type: Object,
                default: function() {
                    return {
                        autoplay: 3500
                    }
                }
            },
            pictures: {
                type: Array
            },
            mainPicture: String,
            videoId: {
                type: Number,
                required: false,
                default: null
            },
            lazyLoad: {
                type: Boolean,
                required: false,
                default: true
            }
        },
        data: () => {
            return {
                strings: {},
                dataPictures: []
            };
        },
        mounted: function() {
            this.strings = this.$store.state.strings['swiper-top'];
            if (!this.swiper && typeof global.window != 'undefined') {
                this.swiper = new Swiper(this.$el, this.options)
            }
            this.$watch('mainPicture', function () {
                this.updateDataPictures();
            });
            this.$watch('pictures', function () {
                this.updateDataPictures();
            });
            this.updateDataPictures();
        },
        updated: function(){
            this.swiper.update();
        },
        beforeDestroy: function() {
            if (!!this.swiper) {
                this.swiper = null;
                delete this.swiper
            }
        },
        methods : {
            lightBoxMe (img) {
                this.$parent.$emit('openLightBox', img.normalUrl)
            },
            updateDataPictures: function () {
                let pictures=[];
                let that = this;
                if(this.pictures){
                    this.pictures.forEach(function (picture) {
                        if(picture.hashName == that.mainPicture){
                            pictures.unshift(picture);
                        } else {
                            pictures.push(picture);
                        }
                    });
                }
                this.dataPictures = pictures;
            }
        }
    }
</script>