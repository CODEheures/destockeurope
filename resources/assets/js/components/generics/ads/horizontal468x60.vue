<template>
    <div class="ui centered banner ad" :style="!isProd ? 'background-image: url(\''+img1+'\'); background-size: contain;':'background: none;'">
        <a :href="url1"><img :src="img1" width="100%"></a>
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