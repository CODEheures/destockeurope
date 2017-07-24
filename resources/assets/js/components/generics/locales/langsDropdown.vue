<template>
    <div :id="'langChoice-'+_uid" class="ui icon top right pointing dropdown right floated basic button langChoice">
        <i class="world icon"></i>
        <div class="menu">
            <div v-for="lang in dataListAvailableLang" class="item" :data-value="lang">{{ lang }}</div>
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
                dataListAvailableLang: {}
            };
        },
        mounted () {
            this.dataListAvailableLang = JSON.parse(this.listAvailableLang);
        },
        methods: {

        },
        updated () {
            $('#langChoice-'+this._uid)
                .dropdown({
                    onChange: function(value, text, $selectedItem) {
                        let link_hreflang = $("link[hreflang|="+value+"]");
                        window.location.assign((link_hreflang.attr('href')));
                    }
                })
            ;
        }
    }
</script>
