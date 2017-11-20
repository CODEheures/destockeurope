<template>
        <div :id="_uid" :class="oldCurrency !== undefined && oldCurrency !== null && oldCurrency !== 0 ? 'ui blue mini right pointing dropdown icon button' : 'ui mini right pointing dropdown icon button'">
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
  /**
   * Props
   *  - currenciesList: Array of currencies objects. List of the currencies: [{code: 'EUR', symbol: '€'}, {code: 'USD', symbol: '$'}, ...]
   *  - oldCurrency: String. The current currencie choice on mounted: 'EUR'
   *  - withAll: Boolean. To add an All item choice
   *
   * Events:
   * @currencyChoice: emit the currencie choice: 'EUR'
   */
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
      let that = this
      $('#' + this._uid)
        .dropdown('set selected', that.oldCurrency)
        .dropdown({
          fullTextSearch: true,
          forceSelection: false,
          onChange (value, text, $selectedItem) {
            if (value === 0) {
              that.$emit('currencyChoice', null)
            }
            else {
              that.$emit('currencyChoice', value)
            }
          }
        })
    }
  }
</script>
