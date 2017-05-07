<template>
    <div class="ui centered half banner ad">
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
                default: '/images/ads/bandeau234x60.jpg'
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