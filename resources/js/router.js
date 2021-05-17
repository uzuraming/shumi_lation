import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Home from './pages/home.vue'
import Login from './pages/Login.vue'
import Signup from './pages/Signup.vue'
import Timeline from './pages/Timelines.vue'
import Request from './pages/Requests.vue'
import Favorite from './pages/Favorites.vue'
import Work from './pages/Works.vue'
import Comment from './pages/Comments.vue'
import UserDetail from './pages/users/UserDetail.vue'
import EditUser from './pages/users/EditUser.vue'

import WorkDetail from './pages/WorksDetail.vue'
import WorkCreate from './pages/WorksCreate.vue'

import store from './store'

import SystemError from './pages/errors/System.vue'

import Chat from './pages/Chat.vue'
// import ChatDetail from './pages/ChatDetail.vue'

import WaitVerify from './pages/WaitVerify.vue'
import NotFound from './pages/errors/NotFound.vue'

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
      path: '*',
      component: NotFound,
      name:'notfound'
    },
    
    {
        path: '/timelines',
        component: Favorite,
        props: route => {
            const page = route.query.page
            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1, pageName:'timelines' }
        }
    },
    {
      path: '/favorites',
      component: Favorite,
      beforeEnter (to, from, next) {
        if (!store.getters['auth/check'] || !store.getters['auth/is_verified']) {
          next('/')
        } else {
          next()
        }
      },
      props: route => {
        const page = route.query.page
        return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1, pageName:'favorites' }
      }
    },
    {
      path: '/requests',
      component: Request,
      props: route => {
        const page = route.query.page
        return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1, pageName:'requests' }
      },
      beforeEnter (to, from, next) {
        if (!store.getters['auth/check'] || !store.getters['auth/is_verified']) {
          next('/')
        } else {
          next()
        }
      },
    },
    {
        path: '/works',
        component: Work,
        props: route => {
            const page = route.query.page
            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1,  pageName:'works' }
        }
    },
    {
        path: '/bookmarks',
        component: Work,
        props: route => {
            const page = route.query.page
            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1, pageName:'bookmarks' }
        },
        beforeEnter (to, from, next) {
          if (!store.getters['auth/check'] || !store.getters['auth/is_verified']) {
            next('/')
          } else {
            next()
          }
        },
    },
    {
      name:'workCreate',
      path: '/works/create',
      component: WorkCreate,
      beforeEnter (to, from, next) {
        if (!store.getters['auth/check'] || !store.getters['auth/is_verified']) {
          next('/')
        } else {
          next()
        }
      },
    },
    {
        name:'workDetail',

        path: '/works/:work_id',
        component: WorkDetail,
        props: true
    },
    {
        name:'editWork',

        path: '/works/:work_id/edit',
        component: WorkCreate,
        props: {
          pageName:'editWork'
        },
        beforeEnter (to, from, next) {
          if (!store.getters['auth/check'] || !store.getters['auth/is_verified']) {
            next('/')
          } else {
            next()
          }
        },
    },
    {
      name:'comments',
      path: '/works/:work_id/comments',
      component: Comment,
      props: route => {
          const page = route.query.page
          return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1, pageName:'comments' }
      }
    },

    {
        name:'userDetail',
        path: '/users/:user_id',
        component: UserDetail,
        props: true
    },
    {
        name:'editUser',
        path: '/users/:user_id/edit',
        component: EditUser,
        props: true,
        beforeEnter (to, from, next) {
          if (!store.getters['auth/check'] || !store.getters['auth/is_verified']) {
            next('/')
          } else {
            next()
          }
        },
    },
    
    
  {
    path: '/',
    component: Home,
    name:'home',
    beforeEnter (to, from, next) {
      if (store.getters['auth/check'] || store.getters['auth/is_verified']) {
        next('/timelines')
      } else {
        next()
      }
    },
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
  },
  {
    path: '/chats',
    component: Chat,
    props: route => {
        const page = route.query.page
        return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1}
    },
    beforeEnter (to, from, next) {
      if (!store.getters['auth/check'] || !store.getters['auth/is_verified']) {
        next('/')
      } else {
        next()
      }
    },
  },
  {
    path: '/chats/:chat_room_id',
    name:'chatDetail',
    component: () => import( 'resources/js/pages/ChatDetail.vue'),
    props: true,
    beforeEnter (to, from, next) {
      if (!store.getters['auth/check'] || !store.getters['auth/is_verified']) {
        next('/')
      } else {
        next()
      }
    },
  },
  {
    path: '/wait_verify',
    name:'waitVerify',
    component: WaitVerify,
    beforeEnter (to, from, next) {
      if (!store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    },
  },
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history', // ★ 追加
  routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router