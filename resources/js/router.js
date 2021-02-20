import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Home from './pages/home.vue'
import Login from './pages/Login.vue'
import Signup from './pages/Signup.vue'
import Timeline from './pages/Timelines.vue'
import Work from './pages/Works.vue'
import UserDetail from './pages/users/UserDetail.vue'

import WorkDetail from './pages/WorksDetail.vue'

import store from './store'

import SystemError from './pages/errors/System.vue'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
    {
        path: '/500',
        component: SystemError
    },
    {
        path: '/timelines',
        component: Timeline,
        props: route => {
            const page = route.query.page
            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
        }
    },
    {
        path: '/works',
        component: Work,
        props: route => {
            const page = route.query.page
            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
        }
    },
    {
        name:'workDetail',

        path: '/works/:work_id',
        component: WorkDetail,
        props: true
    },
    {
        name:'userDetail',
        path: '/users/:user_id',
        component: UserDetail,
        props: true
    },
    
  {
    path: '/',
    component: Home
  },
  {
    path: '/login',
    component: Login,
    beforeEnter (to, from, next) {
        if (store.getters['auth/check']) {
          next('/')
        } else {
          next()
        }
    }
  },
  {
    path: '/signup',
    component: Signup,
    beforeEnter (to, from, next) {
        if (store.getters['auth/check']) {
          next('/')
        } else {
          next()
        }
    }
  }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history', // ★ 追加
  routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router