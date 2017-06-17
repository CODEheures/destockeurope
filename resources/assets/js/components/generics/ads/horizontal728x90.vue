<template>
    <div class="ui leaderboard ad" :style="!isProd ? 'background-image: url(\''+img1+'\'); background-size: contain;':'background: none;'">
        <!-- horizontal728x90 -->
        <adsense
                ad-client="ca-pub-9425842434111721"
                ad-slot="7361012294"
                style="display:inline-block;width:728px;height:90px"
        ></adsense>
    </div>
</template>

<script>
    export default {
        props: {
            //Vue vars
            'img1': {
                required: false,
                type: String,
                default: '/images/ads/bandeau728x90.jpg'
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