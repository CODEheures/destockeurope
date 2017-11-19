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
  import Axios from 'axios'
  export default {
    props: {
      oldLocale: {
        type: String
      }
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      },
      strings () {
        return this.$store.state.strings['locales-dropdown-2']
      }
    },
    watch: {
      isReady () {
        this.setOldChoice()
      },
      oldLocale () {
        this.setOldChoice()
      }
    },
    data () {
      return {
        locales: [],
        countLocales: 0,
        isLoaded: false,
        isReady: false
      }
    },
    mounted () {
      this.getListLocales()
      let that = this
      $('#' + this._uid)
        .dropdown({
          fullTextSearch: true,
          forceSelection: false,
          onChange (value, text, $selectedItem) {
            if (value !== undefined && value !== null && value !== '') {
              that.$emit('localeChoice', value)
            }
          }
        })
      this.setReady()
    },
    methods: {
      getListLocales () {
        this.isLoaded = false
        let that = this
        Axios.get(this.properties.routeListLocales)
          .then(function (response) {
            that.locales = response.data
            that.countLocales = Object.keys(response.data.listLocales).length
            that.isLoaded = true
          })
          .catch(function () {
            that.$emit('loadError')
          })
      },
      setReady () {
        let that = this
        this.$watch('isLoaded', function () {
          let testLoadedInterval = setInterval(function () {
            if ($('#' + that._uid).find('.item').length === that.countLocales) {
              that.isReady = true
              clearInterval(testLoadedInterval)
            }
          }, 200)
        })
      },
      setOldChoice () {
        if (this.oldLocale !== '' && this.oldLocale in this.locales.listLocales) {
          $('#' + this._uid).dropdown('set selected', this.oldLocale)
        }
        else {
          $('#' + this._uid).dropdown('set selected', this.locales.userPrefLocale)
        }
      }
    }
  }
</script>
