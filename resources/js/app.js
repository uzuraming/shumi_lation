import './bootstrap'
import Vue from 'vue'
// ルーティングの定義をインポートする
import router from './router'
// ルートコンポーネントをインポートする
import App from './App.vue'
import store from './store'


require("bootstrap-css-only/css/bootstrap.min.css");
require("mdbvue/lib/css/mdb.min.css");
require("@fortawesome/fontawesome-free/css/all.min.css");

const createApp = async () => {
    await store.dispatch('auth/currentUser')
  
    new Vue({
      el: '#app',
      router,
      store,
      components: { App },
      template: '<App />'
    })
  }
  
createApp()