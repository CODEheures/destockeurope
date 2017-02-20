<template>
        <div :id="_uid" :class="dataCurrent!=0 ? 'ui blue mini right pointing dropdown icon button' : 'ui mini right pointing dropdown icon button'">
            <i class="setting icon"></i>
            <div class="menu">
                <div class="ui right search icon input">
                    <i class="search icon"></i>
                    <input type="text" :placeholder="inputSearchLabel">
                </div>
                <div class="divider"></div>
                <div class="scrolling menu">
                    <div class="item" v-if="withAll" :data-value="0">
                        {{ withAllLabel }}
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
            firstMenuName: String,
            inputSearchLabel: String,
            withAllLabel: String,
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
        data: () => {
            return {
                dataCurrent: 0
            };
        },
        mounted () {
            let that = this;
            $('#'+this._uid)
                .dropdown('set selected', that.oldCurrency)
                .dropdown({
                    fullTextSearch: true,
                    forceSelection: false,
                    onChange: function (value, text, $selectedItem) {
                        that.dataCurrent =  value;
                        if(value==0){
                            that.$parent.$emit('currencyChoice', {cur: null});
                        } else {
                            that.$parent.$emit('currencyChoice', {cur: value});
                        }
                    }
                })
            ;
        },
        methods: {

        }
    }
</script>
