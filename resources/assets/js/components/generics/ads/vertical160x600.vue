<template>
    <div :class="centered==true ? 'ui centered wide skyscraper ad welcome-ads' : 'ui wide skyscraper ad welcome-ads'">
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