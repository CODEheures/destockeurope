<template>
    <div>
        <div class="ui small rectangle ad ">
            <a :href="url1"><img :src="img1" width="100%"></a>
        </div>
        <div class="ui small rectangle ad spaced-top-2">
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
            }
        },
        data: () => {
            return {

            }
        },
        mounted () {

        },
        updated () {

        },
        methods: {

        }
    }
</script>