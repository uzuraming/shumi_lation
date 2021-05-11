<template>
  

  <!-- フッターのコンポーネント -->
  <!-- ログインやユーザー登録やホーム画面では表示しない -->
  <div class="fixed-bottom white shadow d-flex justify-content-center align-items-center" v-if="$route.name!='home'&& $route.path!='/login' && $route.path!='/signup' && $route.name != 'notFound'">
      <ul class="nav nav-pills">
        <!-- タイムライン -->
        <li class="nav-item">
          <router-link class="nav-link mx-2" style="text-decoration: none;"  :class="{'active':$route.path.match(/timelines/)}" to="/timelines"><mdb-icon class="h3 " color="gray" icon="align-left" /></router-link>
        </li>
        <!-- 作品 -->
        <li class="nav-item nav-pills ">
          <router-link class="nav-link mx-2" style="text-decoration: none;" to="/works" :class="{'active':$route.path.match(/works/)}" ><mdb-icon class="h3" color="gray" icon="book" /></router-link>
        </li>
        <!-- ブックマーク -->
        <li class="nav-item nav-pills">
          <router-link v-if="isLogin && isVerified" class="nav-link mx-2" style="text-decoration: none;" to="/bookmarks" :class="{'active':$route.path.match(/bookmarks/)}"><mdb-icon class="h3" color="gray"  icon="bookmark" /></router-link>
        </li>
        <!-- チャット -->
        <li class="nav-item nav-pills">
          <router-link v-if="isLogin && isVerified" class="nav-link mx-2" style="text-decoration: none;" to="/chats" :class="{'active':$route.path.match(/chats/)}"><mdb-icon class="h3" color="gray" icon="comment-alt" /></router-link>
        </li>

    </ul>
  </div>

  
</template>

<script>
  // mdbからパッケージをインポート
  import { mdbNavbar, mdbNavbarBrand, mdbNavbarToggler, mdbNavbarNav, mdbNavItem, mdbInput, mdbIcon } from 'mdbvue';
  export default {
    name: 'NavbarPage',
    components: {
      mdbNavbar,
      mdbNavbarBrand,
      mdbNavbarToggler,
      mdbNavbarNav,
      mdbNavItem,
      mdbInput,
      mdbIcon 
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
      // ユーザーの名前を返す
      username () {
        return this.$store.getters['auth/username']
      },

    },
  }
</script>
<style scoped>
 a{
   text-decoration:none;
   color: gray;
 }
</style>

