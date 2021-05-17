<template>
  <div class="row">
    <div class="col-sm-0 col-lg-3"></div>
    <div class="col-sm-12 col-lg-6">
      <div class="text-center mt-5"> 
        <!-- <img src="/images/vendor/mdbvue/lib/svg/羽根のペンの無料アイコン素材.svg" class="img-fluid px-5 w-75" alt="Responsive image"> -->
        <h2 class="mb-5 login-title">Welcome</h2>
      </div>
      <div class="text-center">
        <button class="btn btn-elegant shadow-none w-100" @click="$router.push({path:'/signup'})">
          ユーザー登録
        </button>
        <button class="btn btn-outline-dark shadow-none w-100 mb-3" @click="$router.push({path:'/login'})">
          ログイン
        </button>
        <router-link :to="{path:'/timelines'}" class="mt-5">
          
          ゲストとして使ってみる
        </router-link>
        <div>

        </div>

      </div>
      
    </div>
    <div class="col-sm-0 col-lg-3"></div>
 
    
  </div>

</template>
<script>

  export default {
    components: {
      
  
    }
  }
</script>

<style scoped>
.wrapper-img{
  display: flex;
  flex-wrap: wrap;
}
.img-div{
  position: relative;
  width: 100%;
}
.img-div:before{
  content: "";
  display: block;
  padding-top: 100%;
}
.wrapper-img img{
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  margin: auto;
  object-fit: cover;

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
.img-writer{
    position: absolute;
    top: 0;
    left: 0;
}
.img-bottom{
    position: absolute;
    bottom: 0;
    right: 0;
}

body, html {
    height: 100%;
  }
  .bg {
    /* The image used */
    background-image: url("https://mdbootstrap.com/img/Photos/Horizontal/Nature/full page/img(20).jpg");

    /* Full height */
    height: 100%;

    /* Center and scale the image nicely */
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
  }

</style>