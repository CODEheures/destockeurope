<template>
    <div :id="_uid" style="width: 100%">
        <swiper :options="swiperOptionTop" class="gallery-top" :style="'height: ' + dataHeightTop + 'px;'">
            <template v-for="picture in pictures" v-if="!picture.isThumb">
                <swiper-slide :style="">
                    <img :data-src="picture.url" class="swiper-lazy" v-on:click="lightBoxMe(picture.url)">
                    <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                </swiper-slide>
            </template>
            <div class="swiper-pagination" slot="pagination"></div>
            <div class="swiper-button-next" slot="button-next"><i class="huge chevron right icon"></i></div>
            <div class="swiper-button-prev" slot="button-prev"><i class="huge chevron left icon"></i></div>
        </swiper>
        <swiper :options="swiperOptionThumbs" class="gallery-thumbs" :style="'height: ' + dataHeightThumb + 'px;'">
            <template v-for="picture in pictures" v-if="picture.isThumb">
                <swiper-slide :style="'background-image:url('+picture.url+')'"></swiper-slide>
            </template>
        </swiper>
    </div>
</template>

<script>
    export default {
        props: {
            //vue routes
            //vue vars
            pictures: {
                type: Array
            },
            imageRatio: {
                type: Number
            }
            //vue strings
        },
        data: () => {
            return {
                dataHeightTop: 500,
                dataHeightThumb: 100,
                swiperOptionTop: {
                    name: 'swiperTop',
                    pagination: '.swiper-pagination',
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    paginationType: 'progress',
                    spaceBetween: 10,
                    preloadImages: false,
                    // Enable lazy loading
                    lazyLoading: true,
                    observer: true,
                    zoom: true
                },
                swiperOptionThumbs: {
                    name: 'swiperThumbs',
                    spaceBetween: 10,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    touchRatio: 0.2,
                    slideToClickedSlide: true
                }
            };
        },
        mounted () {
            const swiperTop = this.$children.find((children) => children.options.name == 'swiperTop').swiper;
            const swiperThumbs = this.$children.find((children) => children.options.name == 'swiperThumbs').swiper;
            swiperTop.params.control = swiperThumbs;
            swiperThumbs.params.control = swiperTop;
            this.dataHeightTop = $('#'+this._uid).width()/this.imageRatio;
            this.dataHeightThumb = this.dataHeightTop*20/80;
        },
        methods: {
            lightBoxMe (imgUrl) {
                this.$parent.$emit('openLightBox', imgUrl)
            }
        }
    }
</script>