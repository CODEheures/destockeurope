<template>
        <div id="currencies-dropdown-menu" class="ui search selection dropdown label">
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
            update: Boolean,
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
            this.$watch('update', function() {this.getListCurrencies();});
        },
        methods: {
            getListCurrencies: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                this.$http.get(this.routeListCurrencies)
                        .then(
                                (response) => {
                                    this.currencies = response.data;
                                    this.isLoaded = true;
                                    this.$parent.$emit('currencyChoice', {cur: this.currencies.userPrefCurrency, initial:true});
                                },
                                (response) => {
                                    this.$parent.$emit('loadError');
                                }
                        );
            }
        },
        updated () {
            if (this.oldCurrency != undefined && this.oldCurrency != '') {
                this.currencies.userPrefCurrency = this.oldCurrency;
            }

            var that = this;
            $('#currencies-dropdown-menu')
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
