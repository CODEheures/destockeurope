import { DestockMap } from './destockMap'

window.initAutocomplete = function () {
  window.destockMap = new DestockMap()
  window.destockMap.initAutocomplete('filter_location')
}

window.initMap = function () {
  window.destockMap = new DestockMap()
}

window.$ = window.jQuery = require('jquery')
window.ionRangeSlider = require('ion-rangeslider')
window.Parser = require('url')
window.axios = require('axios')
window.Vuex = require('vuex')

require('amcharts3/amcharts/amcharts')
require('amcharts3/amcharts/gauge')
require('amcharts3/amcharts/serial')
require('amcharts3/amcharts/themes/light')

require('vue-focus')
