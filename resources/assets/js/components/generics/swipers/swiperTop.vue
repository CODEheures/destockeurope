<template>
    <div class="swiper-container">
        <div class="swiper-pagination"></div>
        <div class="swiper-wrapper">
            <div class="swiper-slide" v-if="videoId != null && videoId != ''">
                <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + videoId" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
            <div class="swiper-slide" v-for="picture in pictures" v-if="!picture.isThumb">
                <img :data-src="picture.url" class="swiper-lazy" v-on:click="lightBoxMe(picture.url)">
                <div class="swiper-lazy-preloader swiper-lazy-preloader-black"></div>
            </div>
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
            videoId: {
                type: Number,
                required: false,
                default: null
            }
        },
        mounted: function() {
            if (!this.swiper && typeof global.window != 'undefined') {
                this.swiper = new Swiper(this.$el, this.options)
            }
        },
        updated: function(){
            this.swiper.update()
        },
        beforeDestroy: function() {
            if (!!this.swiper) {
                this.swiper = null;
                delete this.swiper
            }
        },
        methods : {
            lightBoxMe (imgUrl) {
                this.$parent.$emit('openLightBox', imgUrl)
            }
        }
    }
</script>