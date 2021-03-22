<template>
  <div class="wrapper">
      
    
     <header>
         <Navbar/>
     </header>
     <EmailVerifiedMessage/>
     <main class="mt-3">
      <div class="container">
        <RouterView />
      </div>
     
    </main>


    <!-- <footer>
         <Footer/>
     </footer> -->
  </div>
</template>

<script>
    import { INTERNAL_SERVER_ERROR } from './util'

    import Navbar from './components/Navbar.vue'
    import Footer from './components/Footer.vue'
    import Tabs from './components/Tabs.vue'
    import EmailVerifiedMessage from './components/EmailVerifiedMessage.vue'
    

    export default {
        components: {
            Navbar,
            Footer,
            Tabs,
            EmailVerifiedMessage
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

    @import url('https://fonts.googleapis.com/css2?family=Julius+Sans+One&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200&display=swap');

    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@600&display=swap');
    .wrapper{
        font-family: 'Noto Serif JP', serif;
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

