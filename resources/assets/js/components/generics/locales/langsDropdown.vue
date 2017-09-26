<template>
    <div :id="'langChoice-'+_uid" :class="'ui top right pointing dropdown right floated ' + color + ' ' + size + ' button'">
        <div class="text">{{ properties.actualLocale }}</div>
        <!--<i class="dropdown icon"></i>-->
        <div class="menu">
            <div v-for="lang in dataListAvailableLang" class="item" :data-value="lang" v-on:click="goTo(lang)">{{ lang }}</div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            listAvailableLang: String,
            size: {
                type: String,
                default: '',
                required: false,
            },
            color: {
                type: String,
                default: 'basic',
                required: false,
            }
        },
        data: () => {
            return {
                properties: {},
                dataListAvailableLang: null
            };
        },
        mounted () {
            this.properties = this.$store.state.properties['global'];
            this.dataListAvailableLang = JSON.parse(this.listAvailableLang);
        },
        methods: {
            goTo(lang) {
                let link_hreflang = $("link[hreflang|="+lang+"]");
                DestockTools.goToUrl((link_hreflang.attr('href')));
            }
        },
        updated () {
            $('#langChoice-'+this._uid).dropdown()
            ;
        }
    }
</script>
