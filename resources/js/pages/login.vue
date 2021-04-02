<template>
    <div>
        <!-- Default form login -->
 
        <form @submit.prevent="login" class="mt-5">
            <p class="h3 text-center mb-4 login-title">Sign in</p>
            <mdb-input label="Your email" v-model="loginForm.email" type="email" id="defaultFormLoginEmailEx" />
            <mdb-input label="Password" v-model="loginForm.password" type="password" id="defaultFormLoginPasswordEx"/>
            <div class="text-center mt-4">
                <button class="btn btn-dark shadow-none rounded-0" type="submit">Login</button>
            </div>
        </form>
        <!-- Default form login -->

        <mdb-modal :show="loginErrors !=null" @close="loginErrors == null">
            <mdb-modal-header :close="false" >
                <mdb-modal-title>警告</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                <div v-if="loginErrors != null" class="errors">
                    <ul v-if="loginErrors.email">
                        <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
                    </ul>
                    <ul v-if="loginErrors.password">
                        <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
                    </ul>
                </div>
            </mdb-modal-body>
            <mdb-modal-footer>
                <mdb-btn color="secondary" @click.native="clearError()">Close</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>
    </div>
  
  
</template>

<script>
import { mdbModal, mdbModalHeader, mdbModalTitle, mdbModalBody, mdbModalFooter, mdbBtn, mdbInput, } from 'mdbvue';
export default {
    components: {
      mdbModal,
      mdbModalHeader,
      mdbModalTitle,
      mdbModalBody,
      mdbModalFooter,
      mdbBtn, 
      mdbInput,
    },
    data(){
        return{
            loginForm:{
                email:"",
                password:"",
            }
        }
    },
    methods:{
        async login(){
            // authストアのloginアクションを呼び出す
            await this.$store.dispatch('auth/login', this.loginForm)

            if (this.apiStatus) {
                // トップページに移動する
                this.$router.push('/')
            }
        },
        clearError () {
            this.$store.commit('auth/setLoginErrorMessages', null)
        }
    },
    computed:{
        apiStatus () {
            return this.$store.state.auth.apiStatus
        },
        loginErrors () {
            return this.$store.state.auth.loginErrorMessages
        },
        
    },
    created () {
        this.clearError()
    }
}
</script>

<style scoped>
    .login-title{
        font-family: 'Julius Sans One', sans-serif;
    }
</style>