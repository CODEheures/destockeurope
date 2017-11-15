<template>
        <div :id="_uid" :class="oldCurrency !== 0 && oldCurrency !== null && oldCurrency !== undefined ? 'ui blue mini right pointing dropdown icon button' : 'ui mini right pointing dropdown icon button'">
            <i class="setting icon"></i>
            <div class="menu">
                <div class="ui right search icon input">
                    <i class="search icon"></i>
                    <input type="text" :placeholder="strings.inputSearchLabel">
                </div>
                <div class="divider"></div>
                <div class="scrolling menu">
                    <div class="item" v-if="withAll" :data-value="0">
                        {{ strings.withAllLabel }}
                    </div>
                    <div class="divider" v-if="withAll"></div>
                    <div v-for="(currency, key) in currenciesList" class="item" :data-value="currency.code">
                        {{ currency.code }} <div class="ui horizontal label">{{ currency.symbol }}</div>
                    </div>
                </div>
            </div>
        </div>
</template>


<script>
    export default {
        props: {
            currenciesList: Array,
            oldCurrency: {
                type: String,
                required: false,
                default: '0'
            },
            withAll: {
                type: Boolean,
                default: false,
                required: false
            }
        },
        computed: {
            strings () {
                return this.$store.state.strings['currencies-button']
            }
        },
        mounted () {
            let that = this;
            $('#'+this._uid)
                .dropdown('set selected', that.oldCurrency)
                .dropdown({
                    fullTextSearch: true,
                    forceSelection: false,
                    onChange: function (value, text, $selectedItem) {
                        if(value==0){
                            that.$emit('currencyChoice', null);
                        } else {
                            that.$emit('currencyChoice', value);
                        }
                    }
                })
            ;
        }
    }
</script>
