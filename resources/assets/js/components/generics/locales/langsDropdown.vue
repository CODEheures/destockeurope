<template>
    <div :id="'langChoice-'+_uid" class="ui top right pointing dropdown right floated basic button">
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
                window.location.assign((link_hreflang.attr('href')));
            }
        },
        updated () {
            $('#langChoice-'+this._uid).dropdown()
            ;
        }
    }
</script>
