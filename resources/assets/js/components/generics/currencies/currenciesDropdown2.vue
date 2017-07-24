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
                    <div class="ui left icon input">
                        <i class="search icon"></i>
                        <input type="text" :placeholder="strings.inputSearchLabel">
                    </div>
                    <div class="divider"></div>
                    <div class="scrolling menu">
                        <div v-for="(currency, key) in currencies.listCurrencies" class="item" :data-value="key">
                            <span class="text">{{ currency.code }} {{ currency.symbol }}</span>
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
            update: Boolean,
            oldCurrency: {
                type: String,
                required: false
            }
        },
        data: () => {
            return {
                strings: {},
                properties: {},
                currencies: [],
                isLoaded: false,
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['currencies-dropdown-2'];
            this.properties = this.$store.state.properties['global'];
            this.getListCurrencies();
            this.$watch('update', function() {this.getListCurrencies();});
        },
        methods: {
            getListCurrencies: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                axios.get(this.properties.routeListCurrencies)
                    .then(function (response) {
                        that.currencies = response.data;
                        that.isLoaded = true;
                        let userPrefCurrencySubUnit = that.currencies.listCurrencies[that.currencies.userPrefCurrency]['subunit'];
                        let userPrefCurrencySymbol = that.currencies.listCurrencies[that.currencies.userPrefCurrency]['symbol'];
                        that.$parent.$emit('currencyChoice', {cur: that.currencies.userPrefCurrency, subunit: userPrefCurrencySubUnit, symbol: userPrefCurrencySymbol, initial:true});
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError');
                    });
            }
        },
        updated () {
            if (this.oldCurrency != undefined && this.oldCurrency != '') {
                this.currencies.userPrefCurrency = this.oldCurrency;
                if('listCurrencies' in this.currencies){
                    let userPrefCurrencySubUnit = this.currencies.listCurrencies[this.currencies.userPrefCurrency]['subunit'];
                    let userPrefCurrencySymbol = this.currencies.listCurrencies[this.currencies.userPrefCurrency]['symbol'];
                    this.$parent.$emit('currencyChoice', {cur: this.currencies.userPrefCurrency, subunit: userPrefCurrencySubUnit, symbol: userPrefCurrencySymbol, initial:false});
                }
            }

            let that = this;
            $('#'+this._uid)
                    .dropdown('set selected', that.currencies.userPrefCurrency)
                    .dropdown({
                        fullTextSearch: true,
                        forceSelection: false,
                        onChange: function (value, text, $selectedItem) {
                            if(value != that.currencies.userPrefCurrency){
                                let subUnit = that.currencies.listCurrencies[value]['subunit'];
                                let symbol = that.currencies.listCurrencies[value]['symbol'];

                                that.$parent.$emit('currencyChoice', {cur: value, subunit: subUnit, symbol: symbol, initial:false});
                                that.currencies.userPrefCurrency = value;
                            }
                        }
                    })
            ;
        }
    }
</script>
