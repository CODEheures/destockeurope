<template>
        <div :id="_uid" class="ui floating dropdown labeled icon button">
            <i class="world icon"></i>
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <span class="text">{{ firstMenuName }}</span>
            <div class="menu">
                <div class="ui icon search input">
                    <i class="search icon"></i>
                    <input type="text" :placeholder="inputSearchLabel">
                </div>
                <div class="divider"></div>
                <div class="scrolling menu">
                    <div v-for="(locale, key) in locales.listLocales" class="item" :data-value="key">
                        <i class="flag" :class="locale.region"></i> {{ locale.name }} <div class="ui horizontal label">{{ locale.code }}</div>
                    </div>
                </div>
            </div>
        </div>
</template>


<script>
    export default {
        props: {
            routeListLocales: String,
            firstMenuName: String,
            inputSearchLabel: String,
            oldLocale: {
                type: String,
                required: false
            }
        },
        data: () => {
            return {
                locales: [],
                isLoaded: false,
            };
        },
        mounted () {
            this.getListLocales();
        },
        methods: {
            getListLocales: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                axios.get(this.routeListLocales)
                    .then(function (response) {
                        that.locales = response.data;
                        that.isLoaded = true;
                        that.$parent.$emit('localeChoice', {locale: that.locales.userPrefLocale, initial:true});
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError');
                    });
            }
        },
        updated () {
            if (this.oldLocale != undefined && this.oldLocale != '') {
                this.locales.userPrefLocale = this.oldLocale;
            }

            let that = this;
            $('#'+this._uid)
                    .dropdown('set selected', that.locales.userPrefLocale)
                    .dropdown({
                        fullTextSearch: true,
                        forceSelection: false,
                        onChange: function (value, text, $selectedItem) {
                            if(value != that.locales.userPrefLocale){
                                that.$parent.$emit('localeChoice', {locale: value});
                                that.locales.userPrefLocale = value;
                            }
                        }
                    })
            ;
        }
    }
</script>
