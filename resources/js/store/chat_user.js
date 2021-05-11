

// チャットしているユーザー
const state = {
    chat_user:""

}

// 
const getters = {
    chat_user:state => state.chat_user,

}

const mutations = {
    setChatUser (state, user) {
        state.chat_user = user+'との'
    },
    clearChatUser(state){
        state.chat_user = ""
    }

}

const actions = {
    changeChatUser(context,user){
        context.commit('setChatUser', user)
    },
    clearChatUser(context){
        context.commit('clearChatUser')
    }
  

}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}