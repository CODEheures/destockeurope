<template>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide" v-if="videoId != null && videoId != ''">
                <div style="width:100%; height:100%; background-color: rgba(0,0,0,0.1); position: absolute"></div>
                <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + videoId" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
            <div class="swiper-slide" v-for="picture in pictures" v-if="picture.isThumb"  :style="'background-image:url('+picture.url+')'"></div>
        </div>
    </div>
</template>

<script>
    import Player from '@vimeo/player';
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
            this.swiper.update();
            let iframe = document.getElementById('vimeo-iframe-'+this._uid);
            if(iframe){
                const player = new Player(iframe);
            }
        },
        beforeDestroy: function() {
            if (!!this.swiper) {
                this.swiper = null;
                delete this.swiper
            }
        }
    }
</script>