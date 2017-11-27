import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)
const store = new Vuex.Store({
  state: {
    strings: {},
    properties: {}
  },
  mutations: {
    setStrings (state, payload) {
      state.strings[payload.name] = payload.strings
    },
    setProperties (state, payload) {
      Vue.set(state.properties, payload.name, payload.properties)
    }
  }
})

export default store
