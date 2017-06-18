<template>
        <div :id="_uid" class="ui search selection dropdown label">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <span class="text" >{{ firstMenuName }}</span>
            <i class="dropdown icon"></i>
            <div class="menu">
                <div v-for="(currency, key) in currencies.listCurrencies" class="item" :data-value="key">
                    {{ currency.code }} {{ currency.symbol }}
                </div>
            </div>
        </div>
</template>


<script>
    export default {
        props: {
            routeListCurrencies: String,
            firstMenuName: String,
            oldCurrency: {
                type: String,
                required: false
            }
        },
        data: () => {
            return {
                currencies: [],
                isLoaded: false,
            };
        },
        mounted () {
            this.getListCurrencies();
        },
        methods: {
            getListCurrencies: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                axios.get(this.routeListCurrencies)
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
