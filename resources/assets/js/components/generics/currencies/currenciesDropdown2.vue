<template>
    <div>
        <div class="ui mini labeled right action input">
            <div class="ui blue label">
                {{ strings.firstMenuName }}
            </div>
            <div :id="_uid" class="ui mini floating dropdown button">
                <div class="text" >{{ strings.firstMenuName }}</div>
                <i class="dropdown icon"></i>
                <div class="menu">
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
      oldCurrency: {
        type: String
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['currencies-dropdown-2']
      },
      properties () {
        return this.$store.state.properties['global']
      },
      currencies () {
        return this.$store.state.properties['currencies-dropdown-menu-2']['datas']
      }
    },
    watch: {
      oldCurrency () {
        this.setOldChoice()
      }
    },
    mounted () {
      let that = this
      $('#' + this._uid)
        .dropdown({
          fullTextSearch: true,
          forceSelection: false,
          onChange (value, text, $selectedItem) {
            if (value !== undefined && value !== null && value !== ''){
              let subUnit = that.currencies.listCurrencies[value]['subunit']
              let symbol = that.currencies.listCurrencies[value]['symbol']
              that.$emit('currencyChoice', {cur: value, subunit: subUnit, symbol: symbol})
            }
          }
        })
      this.setOldChoice()
    },
    methods: {
      setOldChoice () {
        if (this.oldCurrency !== '' && this.oldCurrency in this.currencies.listCurrencies) {
          $('#' + this._uid).dropdown('set selected', this.oldCurrency)
        }
        else {
          $('#' + this._uid).dropdown('set selected', this.currencies.userPrefCurrency)
        }
      }
    }
  }
</script>
