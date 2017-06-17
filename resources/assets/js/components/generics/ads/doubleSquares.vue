<template>
    <div>
        <div :class="centered ? 'ui small rectangle centered ad' : 'ui small rectangle ad' " :style="!isProd ? 'background-image: url(\''+img1+'\'); background-size: contain;':'background: none;'">
            <!-- square180x150_1 -->
            <adsense
                    ad-client="ca-pub-9425842434111721"
                    ad-slot="1454079496"
                    style="display:inline-block;width:180px;height:150px"
            ></adsense>
        </div>
        <div :class="centered ? 'ui small rectangle centered ad spaced-top-2' : 'ui small rectangle ad spaced-top-2'" :style="!isProd ? 'background-image: url(\''+img2+'\'); background-size: contain;':'background: none;'">
            <!-- square180x150_2 -->
            <adsense
                    ad-client="ca-pub-9425842434111721"
                    ad-slot="2930812691"
                    style="display:inline-block;width:180px;height:150px"
            ></adsense>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            //Vue vars
            'img1': {
                required: false,
                type: String,
                default: '/images/ads/bandeau180x150.jpg'
            },
            'img2': {
                required: false,
                type: String,
                default: '/images/ads/bandeau180x150_2.jpg'
            },
            'url1': {
                required: false,
                type: String,
                default: '#'
            },
            'url2': {
                required: false,
                type: String,
                default: '#'
            },
            'centered': {
                required: false,
                type: Boolean,
                default: false
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