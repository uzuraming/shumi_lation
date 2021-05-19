<template>
  <div class="wrapper">
      
    
     <header>
         <Navbar/>
     </header>
     <EmailVerifiedMessage/>
     <main class="mt-3 mb-5 ">
      <div class="container">
        <RouterView />
      </div>
     
    </main>


    <footer>
         <Footer/>
     </footer>
  </div>
</template>

<script>
    import { INTERNAL_SERVER_ERROR, NOT_FOUND } from './util'

    import Navbar from './components/Navbar.vue'
  

    

    export default {
        components: {
            Navbar,
            Footer: () => import( './components/Footer.vue'),
            EmailVerifiedMessage: () => import( './components/EmailVerifiedMessage.vue'),
        },
        computed: {
            errorCode () {
                return this.$store.state.error.code
            }
        },
        watch: {
            errorCode: {
                handler (val) {
                    if (val === INTERNAL_SERVER_ERROR) {
                        this.$router.push('/500')
                    }else if (val === NOT_FOUND) {
                        this.$router.push('/not-found')
                    }
                },
                immediate: true
            },
            $route () {
                this.$store.commit('error/setCode', null)
            }
        }

    }
</script>
<style>
    
    /* @import url('https://fonts.googleapis.com/css2?family=Julius+Sans+One&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200&display=swap');

   
    @import url('https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@300&display=swap'); */
.wrapper{
  font-family: 'M PLUS Rounded 1c', sans-serif;
}
    .ql-align-center{
        text-align: center;
    }
    .ql-align-right{
        text-align: right;
    }
    .ql-align-justify{
        text-align: justify;
    }

</style>

