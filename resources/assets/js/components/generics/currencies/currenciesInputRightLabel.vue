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
                        that.$parent.$emit('currencyChoice', {cur: that.currencies.userPrefCurrency, initial:true});
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
                                that.$parent.$emit('currencyChoice', {cur: value});
                                that.currencies.userPrefCurrency = value;
                            }
                        }
                    })
            ;
        }
    }
</script>
