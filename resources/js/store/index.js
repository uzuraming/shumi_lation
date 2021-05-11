import Vue from 'vue'
import Vuex from 'vuex'
import error from './error'
import auth from './auth'
import chat_user from './chat_user'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    chat_user
  }
})

export default store