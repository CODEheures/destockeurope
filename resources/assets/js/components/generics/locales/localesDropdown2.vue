<template>
    <div>
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <div class="ui mini labeled right action input">
            <div class="ui blue label">
                {{ strings.firstMenuName }}
            </div>
            <div :id="_uid" class="ui mini floating dropdown button">
                <div class="text" >{{ strings.firstMenuName }}</div>
                <i class="dropdown icon"></i>
                <div class="menu">
                    <!--<div class="ui left icon input">-->
                        <!--<i class="search icon"></i>-->
                        <!--<input type="text" :placeholder="strings.inputSearchLabel">-->
                    <!--</div>-->
                    <!--<div class="divider"></div>-->
                    <div class="scrolling menu">
                        <div v-for="(locale, key) in locales.listLocales" class="item" :data-value="key" :data-text="locale.name">
                            {{ locale.name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            oldLocale: {
                type: String,
            }
        },
        data: () => {
            return {
                strings: {},
                properties: {},
                locales: [],
                countLocales: 0,
                isLoaded: false,
                isReady: false,
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['locales-dropdown-2'];
            this.properties = this.$store.state.properties['global'];
            this.getListLocales();

            let that = this;
            $('#'+this._uid)
                .dropdown({
                    fullTextSearch: true,
                    forceSelection: false,
                    onChange: function (value, text, $selectedItem) {
                        if(value != undefined && value != ''){
                            that.$parent.$emit('localeChoice', {locale: value});
                        }
                    }
                });

            this.setReady();
            this.$watch('isReady', function () { that.setOldChoice() });
            this.$watch('oldLocale', function () { that.setOldChoice() });
        },
        methods: {
            getListLocales: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                axios.get(this.properties.routeListLocales)
                    .then(function (response) {
                        that.locales = response.data;
                        that.countLocales = Object.keys(response.data.listLocales).length;
                        that.isLoaded = true;
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError');
                    });
            },
            setReady () {
                let that = this;
                this.$watch('isLoaded', function () {
                    let testLoadedInterval = setInterval(function () {
                        if($('#'+that._uid).find('.item').length === that.countLocales) {
                            that.isReady = true;
                            clearInterval(testLoadedInterval);
                        }
                    }, 200);
                });
            },
            setOldChoice () {
                if(this.oldLocale !== '' && this.oldLocale in this.locales.listLocales){
                    $('#'+this._uid).dropdown('set selected', this.oldLocale)
                } else {
                    $('#'+this._uid).dropdown('set selected', this.locales.userPrefLocale)
                }
            }
        }
    }
</script>
