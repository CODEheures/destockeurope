<template>
    <div class="swiper-container">
        <slot name="parallax-bg"></slot>
        <slot name="pagination"></slot>
        <div class="swiper-wrapper">
            <slot></slot>
        </div>
        <slot name="button-prev"></slot>
        <slot name="button-next"></slot>
        <slot name="scrollbar"></slot>
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
                this.swiper = null
                delete this.swiper
            }
        }
    }
</script>