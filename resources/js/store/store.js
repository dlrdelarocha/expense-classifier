import Vuex from 'vuex'
import VuexPersist from 'vuex-persist'
import authModule from './authModule.js'

const vuexPersist = new VuexPersist({
  key: 'my-store',
  storage: window.localStorage
})

export default new Vuex.Store({
  plugins: [vuexPersist.plugin],
  modules: {
    auth: authModule
  }
})
