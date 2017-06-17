<template>
    <div :class="centered==true ? 'ui centered wide skyscraper ad welcome-ads' : 'ui wide skyscraper ad welcome-ads'" :style="'background-image: url(\''+img1+'\'); background-size: contain;'">
        <!-- vertical160x600 -->
        <ins class="adsbygoogle"
             style="display:inline-block;width:160px;height:600px"
             data-ad-client="ca-pub-9425842434111721"
             data-ad-slot="9814951091"></ins>
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
            },
            'centered': {
                required: false,
                type: Boolean,
                default:false
            },
            'reload': {
                required: false,
                type: Boolean,
                default:true
            }
        },
        data: () => {
            return {
                isInitial: true
            }
        },
        mounted () {
            this.$watch('reload', function () {
                if (this.isInitial) {
                    this.isInitial = false;
                } else {
                    console.log('reload ads vertical160x600');
                }
            })
        },
        updated () {

        },
        methods: {

        }
    }
</script>