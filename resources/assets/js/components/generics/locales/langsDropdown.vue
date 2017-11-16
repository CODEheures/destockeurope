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
  import { DestockTools } from '../../../destockTools'
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
        computed: {
            properties () {
                return this.$store.state.properties['global']
            },
            dataListAvailableLang () {
                return JSON.parse(this.listAvailableLang)
            }
        },
        methods: {
            goTo(lang) {
                let link_hreflang = $("link[hreflang|="+lang+"]");
                DestockTools.goToUrl((link_hreflang.attr('href')));
            }
        },
        mounted () {
            $('#langChoice-'+this._uid).dropdown()
        }
    }
</script>
