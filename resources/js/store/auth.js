import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
  registerErrorMessages: null
}

const getters = {
  check: state => !! state.user,
  username: state => state.user ? state.user.name : '',
  is_verified: state => state.user ? !!state.user.email_verified_at : false,
  user_img: state => state.user ? state.user.url:null,
  user_id: state => state.user ? state.user.id:null,
}

const mutations = {
  // ユーザーを設定
  setUser (state, user) {
    state.user = user
  },
  // APIの状態を取得する
  setApiStatus (state, status) {
    state.apiStatus = status
  },
  // ログインメッセージを取得
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages
  },
  // ユーザー登録時のメッセージを取得
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages
  },
  // ログインメッセージを削除
  clearLoginErrorMessages(state){
    state.loginErrorMessages = null
  },
  // ユーザー登録時のメッセージを削除
  clearRegisterErrorMessages(state){
    state.registerErrorMessages = null
  },
}

const actions = {
  // 会員登録
  async register (context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/signup', data)

    if (response.status === CREATED) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setRegisterErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
  },

  // ログイン
  async login (context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/login', data)

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setLoginErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
  },

  // ログアウト
  async logout (context) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/logout')

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', null)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },

  // ログインユーザーチェック
  async currentUser (context) {
    context.commit('setApiStatus', null)
    const response = await axios.get('/api/user')
    const user = response.data || null

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', user)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },

  // エラーメッセージクリア
  clearErrorMessage(context){
    context.commit('clearLoginErrorMessages', null)
    context.commit('clearRegisterErrorMessages', null)

  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}