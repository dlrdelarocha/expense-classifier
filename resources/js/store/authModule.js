import axios from 'axios'
import router from '@/router/index.js'

const state = {
  isLoggedIn: false,
  token: null,
  user: null,
  logoutStarted: false
}

const mutations = {
  setLoggedIn(state, isLoggedIn) {
    state.isLoggedIn = isLoggedIn
  },
  setToken(state, token) {
    state.token = token
  },
  setUser(state, user) {
    state.user = user
  },
  setLoggingOut(state, logoutStarted) {
    state.logoutStarted = logoutStarted
  }
}

const getters = {
  isLoggedIn: (state) => state.isLoggedIn,
  getToken: (state) => state.token,
  getUser: (state) => state.user,
  getLogoutStarted: (state) => state.logoutStarted
}

const actions = {
  async login({ commit }, credentials) {
    const response = await axios.post('/api/login', {
      email: credentials.email,
      password: credentials.password
    })

    const token = response.data.token
    const user = response.data.user

    commit('setUser', user)
    commit('setToken', token)
    commit('setLoggedIn', true)
  },
  async register({ commit }, credentials) {
    const response = await axios.post('/api/register', {
      name: credentials.name,
      email: credentials.email,
      password: credentials.password,
      password_confirmation: credentials.password_confirmation
    })

    const token = response.data.token
    const user = response.data.user

    commit('setUser', user)
    commit('setToken', token)
    commit('setLoggedIn', true)
  },
  async logout({ commit }) {
    clearUserData(commit)
    try {
      await axios.post('/api/logout')
    } catch (e) {
      throw e
    }
  }
}

function clearUserData(commit) {
  commit('setUser', null)
  commit('setToken', null)
  commit('setLoggedIn', false)
}

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
}
