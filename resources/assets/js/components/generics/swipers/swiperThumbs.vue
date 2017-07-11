<template>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide" v-if="videoId != null && videoId != ''">
                <div style="width:100%; height:100%; background-color: rgba(0,0,0,0.1); position: absolute"></div>
                <iframe :id="'vimeo-iframe-'+_uid" :src="'https://player.vimeo.com/video/' + videoId" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
           <div class="swiper-slide" v-for="picture in dataPictures" :style="'background-image:url('+picture+')'"></div>
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
            mainPicture: String,
            videoId: {
                type: Number,
                required: false,
                default: null
            }
        },
        data: () => {
            return {
                dataPictures: []
            };
        },
        mounted: function() {
            if (!this.swiper && typeof global.window != 'undefined') {
                this.swiper = new Swiper(this.$el, this.options)
            }
            this.$watch('mainPictures', function () {
                this.updateDataPictures();
            });
            this.$watch('pictures', function () {
                this.updateDataPictures();
            })
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
        },
        methods : {
            updateDataPictures: function () {
                let pictures=[];
                let that = this;
                this.pictures.forEach(function (picture) {
                    if(!picture.isThumb){
                        if(picture.hashName == that.mainPicture){
                            pictures.unshift(picture.url);
                        } else {
                            pictures.push(picture.url);
                        }
                    }
                });
                this.dataPictures = pictures;
            }
        }
    }
</script>