// Globals Vars and requires
import { DestockMap } from './destockMap'

window.initAutocomplete = function () {
  window.destockMap = new DestockMap()
  window.destockMap.initAutocomplete('filter_location')
}

window.initMap = function () {
  window.destockMap = new DestockMap()
}

window.$ = window.jQuery = require('jquery')

require('../semantic/dist/semantic')
require('ion-rangeslider')
require('amcharts3/amcharts/amcharts')
require('amcharts3/amcharts/gauge')
require('amcharts3/amcharts/serial')
require('amcharts3/amcharts/themes/light')
require('../ripple/ripple')
require('vue-focus')
require('./myjs')
