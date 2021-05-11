<template>
  

  <div>
    <!-- <mdb-navbar class="py-2 shadow-none" animated animation="3"  color="white" light>
    <mdb-navbar-brand href="#">
      Navbar
    </mdb-navbar-brand>
    <mdb-navbar-toggler >
      <mdb-navbar-nav right>
        <mdb-nav-item to="/">Home</mdb-nav-item>
        <mdb-nav-item to="/timelines">TimeLine</mdb-nav-item>
        <mdb-nav-item to="/works">Work</mdb-nav-item>
        <mdb-nav-item v-if="!isLogin" to="/login">login</mdb-nav-item>
        <mdb-nav-item v-if="!isLogin" to="/signup">signup</mdb-nav-item>
        <mdb-nav-item v-if="isLogin && isVerified" to="/requests">Resuests</mdb-nav-item>
        <mdb-nav-item v-if="isLogin && isVerified" to="/chats">Chats</mdb-nav-item>
        <mdb-nav-item v-if="isLogin && isVerified" to="/favorites">favorites</mdb-nav-item>
        <mdb-nav-item v-if="isLogin" href="#" @click.prevent="logout">logout</mdb-nav-item>
      </mdb-navbar-nav>
    </mdb-navbar-toggler>
  </mdb-navbar> -->
  <!-- ヘッダーのナビゲーション -->
    <nav class="navbar navbar-dark bg-dark shadow-none">
      <router-link v-if="!isLogin" @click="isOpen=false"  class="navbar-brand" to="/"><mdb-icon icon="home" /></router-link>
      <router-link v-if="isLogin" @click="isOpen=false"  class="navbar-brand " :to="`/users/${userid}`">
      
      
                            
            <img v-if="userimg" :src="userimg" class="rounded-circle circle_img bg-light" width="40" height="40" alt="...">
            <img v-if="!userimg" class="rounded-circle circle_img bg-light"  src="/images/vendor/mdbvue/lib/svg/iconmonstr-user-5.svg" width="40" height="40" alt="...">
                      
                      
      </router-link>

      <ul class="navbar-nav">　
            <li class="nav-item">
              <div class="nav-link now-page">{{chat_user}}{{now_page}}</div>
            </li>
          </ul>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" @click="isOpen=!isOpen">
        <span class="navbar-toggler-icon"></span>
      </button>
      <transition name="js-accordion" @before-enter="beforeEnter" @enter="enter" @before-leave="beforeLeave" @leave="leave">
      
      
      <div class="navbar-collapse js-accordion--target" id="navbarNav" :class="{ '_state-open': isOpen }" v-show="isOpen">
        <ul class="navbar-nav " >
      
          <!-- <li @click="isOpen=false" class="nav-item"><router-link class="nav-link" to="/">Home</router-link></li> -->
          <li @click="isOpen=false" :class="{'active':$route.path.match(/timelines/)}" class="nav-item"><router-link class="nav-link" to="/timelines">タイムライン</router-link></li>
          <li @click="isOpen=false" :class="{'active':$route.path.match(/works/)}" class="nav-item"><router-link class="nav-link" to="/works">作品</router-link></li>
          <li @click="isOpen=false" :class="{'active':$route.path.match(/login/)}" class="nav-item"><router-link class="nav-link" v-if="!isLogin" to="/login">ログイン</router-link></li>
          <li @click="isOpen=false" :class="{'active':$route.path.match(/signup/)}" class="nav-item"><router-link class="nav-link" v-if="!isLogin" to="/signup">ユーザー登録</router-link></li>
          <!-- <li @click="isOpen=false" class="nav-item"><router-link class="nav-link" v-if="isLogin && isVerified" to="/requests">Resuests</router-link></li> -->
          <li @click="isOpen=false" :class="{'active':$route.path.match(/chats/)}" class="nav-item"><router-link class="nav-link" v-if="isLogin && isVerified" to="/chats">チャット</router-link></li>
          <!-- <li @click="isOpen=false" class="nav-item"><router-link class="nav-link" v-if="isLogin && isVerified" to="/favorites">favorites</router-link></li> -->
          <li @click="isOpen=false" :class="{'active':$route.path.match(/bookmarks/)}" class="nav-item"><router-link class="nav-link" v-if="isLogin && isVerified" to="/bookmarks">ブックマーク</router-link></li>
          <li @click="isOpen=false"  class="nav-item"><a v-if="isLogin" class="nav-link" href="#" @click.prevent="logout">ログアウト</a></li>
        </ul>
      </div>
      </transition>
      
    </nav>

  </div>
</template>

<script>
  import { mdbDropdown, mdbDropdownToggle, mdbDropdownMenu, mdbDropdownItem, mdbContainer, mdbNavbar, mdbNavbarBrand, mdbNavbarToggler, mdbNavbarNav, mdbNavItem, mdbIcon, } from 'mdbvue';
  export default {
    name: 'Navbar',
    components: {
      mdbNavbar,
      mdbNavbarBrand,
      mdbNavbarToggler,
      mdbNavbarNav,
      mdbNavItem,
      mdbContainer,
      mdbDropdown,
      mdbDropdownToggle,
      mdbDropdownMenu,
      mdbIcon,
      mdbDropdownItem
    },
    methods:{
      // ログアウトする
      async logout () {
        await this.$store.dispatch('auth/logout')
        this.$router.push('/login')
      },
      // 以下ヘッダーの伸縮に関する処理
      beforeEnter: function(el) {
        el.style.height = '0';
      },
      enter: function(el) {
        el.style.height = el.scrollHeight + 'px';
      },
      beforeLeave: function(el) {
        el.style.height = el.scrollHeight + 'px';
      },
      leave: function(el) {
        el.style.height = '0';
      },
    },
    data(){
      return {
        isOpen:false // ハンバーガーメニューが開いているか
      }
      
    },
    computed: {
      // ログインしているか
      isLogin () {
        return this.$store.getters['auth/check']
      },
      // メール認証しているか
      isVerified () {
        return this.$store.getters['auth/is_verified']
      },
      // ログイン中のユーザーの名前を取得
      username () {
        return this.$store.getters['auth/username']
      },
      // ログイン中のユーザーのIDを取得
      userid(){
        return this.$store.getters['auth/user_id']
      },
      // ログイン中のユーザーの画像を取得
      userimg(){
        return this.$store.getters['auth/user_img']
      },
      // 今のページの名前を取得
      now_page(){
        if(this.$route.path.match(/timelines/)){
          return 'タイムライン'
        }else if(this.$route.path.match(/works/)){
          return 'みんなの作品'
        }else if(this.$route.path.match(/chat/)){
          return 'チャット'
        }else if(this.$route.path.match(/bookmarks/)){
          return 'ブックマークした作品'
        }else if(this.$route.path.match(/favorites/)){
          return 'いいねしたタイムライン'
        }
      },
      // チャットしている相手のユーザー
      chat_user(){
        return this.$store.getters['chat_user/chat_user']
      }

    },
    watch:{
      $route (to, from){
          // ハンバーガーメニューを閉じる
          this.isOpen = false;
          // チャット中のユーザーの名前をを削除する
          this.$store.dispatch('chat_user/clearChatUser')
      }
} 
  }
</script>
<style >
  .nav-style{
    font-family: 'Oswald', sans-serif;
  }
  button .navbar-toggler{
    display: inline-block !important;
  }
  

</style>
 <style lang="scss" scoped>
  .js-accordion{
  background-color: #fff;
  border-color: #666;
  border-style: solid;
  border-width:1px 1px 0 1px;
  border-radius: 2px;
  &:last-child{
    border-bottom-width:1px;
  }
  &--trigger{
    position: relative;
    display: block;
    width: 100%;
    transition: all 0.35s ease-in;
    color: #4d68b7;
    display: block;
    font-size:20px;
    font-weight: bold;
    text-align: left;
    padding: 20px;
    outline: none;
    border: none;
    cursor: pointer;
    &:after{
      display: inline-block;
      width: 0;
      height: 0;
      border: solid transparent;
      content: "";
      border-top-color: #5f6569;
      border-width: 7px;
      position: absolute;
      top: 50%;
      right: 1em;
      margin-top: -5px;
      transition: all 0.2s ease-in;
    }
    &._state-open{
      background-color: #f1f1f1;
      text-decoration: none;
      &:after{
        transform:rotateX(180deg);
        margin-top: -10px;
      }
    }
    &:hover{
      background-color: #f1f1f1;
      text-decoration: none;
    }
  }
  &--target{
    overflow: hidden;
    transition: height 0.35s ease-in-out;
  }
  &--body{
    
    padding: 30px;
  }
  &-enter-active{
    animation-duration: .35s;
    animation-fill-mode: both;
    animation-name: js-accordion--anime__opend;
  }
  &-leave-active{
    animation-duration: .35s;
    animation-fill-mode: both;
    animation-name: js-accordion--anime__closed;
  }
}

@keyframes js-accordion--anime__opend {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
   }
}
@keyframes js-accordion--anime__closed {
  0% {
   opacity: 1;
  }

  100% {
    opacity: 0;
  }
}

.img-div {
  width: 100%;/*上下のpaddingと同じにする*/
  height: 0;
  padding-top: 100%;/*widthと同じにする*/
  position: relative;
  overflow: hidden;
}
.img-div .img-box {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
}




</style>