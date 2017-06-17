<template>
    <div class="ui centered banner ad" :style="!isProd ? 'background-image: url(\''+img1+'\'); background-size: contain;':'background: none;'">
        <!-- horizontal468x60 -->
        <ins class="adsbygoogle"
             style="display:inline-block;width:468px;height:60px"
             data-ad-client="ca-pub-9425842434111721"
             data-ad-slot="5884279095"></ins>
    </div>
</template>

<script>
    export default {
        props: {
            //Vue vars
            'img1': {
                required: false,
                type: String,
                default: '/images/ads/bandeau468x60.jpg'
            },
            'url1': {
                required: false,
                type: String,
                default: '#'
            }
        },
        data: () => {
            return {
                isProd: false
            }
        },
        mounted () {
            this.isProd = window.destockShareVar.isProd;
        },
        updated () {

        },
        methods: {

        }
    }
</script>