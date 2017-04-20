<template>
    <div class="ui leaderboard ad">
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