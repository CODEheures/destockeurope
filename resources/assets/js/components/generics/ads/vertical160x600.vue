<template>
    <div class="ui wide skyscraper ad welcome-ads">
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
                default: '/images/ads/bandeau160x600.jpg'
            },
            'url1': {
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