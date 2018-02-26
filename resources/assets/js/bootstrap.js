// Globals Vars and requires
import { DestockMap } from './destockMap'

window.initAutocomplete = function () {
  window.destockMap = new DestockMap()
  window.destockMap.initAutocomplete('filter_location')
}

window.initMap = function () {
  window.destockMap = new DestockMap()
}

require('../ripple/ripple')
require('vue-focus')
require('./myjs')
