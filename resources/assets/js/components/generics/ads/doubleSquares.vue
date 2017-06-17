<template>
    <div>
        <div :class="centered ? 'ui small rectangle centered ad' : 'ui small rectangle ad' " :style="!isProd ? 'background-image: url(\''+img1+'\'); background-size: contain;':'background: none;'">
            <a :href="url1"><img :src="img1" width="100%"></a>
        </div>
        <div :class="centered ? 'ui small rectangle centered ad spaced-top-2' : 'ui small rectangle ad spaced-top-2'" :style="!isProd ? 'background-image: url(\''+img2+'\'); background-size: contain;':'background: none;'">
            <a :href="url2"><img :src="img2" width="100%"></a>
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