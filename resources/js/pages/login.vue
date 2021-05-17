<template>
    <div>
        
        <!-- ログインフォーム -->
 
        <form @submit.prevent="login" class="mt-5">
            <p class="h3 text-center mb-4 ">ログイン</p>

            <div class="form-group">
                <label for="login-email" >メールアドレス</label>
                <input type="email" v-model="loginForm.email"  id="login-email" class="form-control">
            </div>

            <div class="form-group">
                <label for="login-password">パスワード</label>
                <input type="password" v-model="loginForm.password" id="login-password" class="form-control">
            </div>


            <!-- <mdb-input label="メールアドレス" v-model="loginForm.email" type="email" id="defaultFormLoginEmailEx" />
            <mdb-input label="パスワード" v-model="loginForm.password" type="password" id="defaultFormLoginPasswordEx"/> -->
            <div class="text-center mt-4">
                <button class="btn btn-dark shadow-none rounded-0" type="submit">ログイン</button>
            </div>
        </form>
        

        <!-- Vログインエラーが出たときに表示されるモーダル -->
        <mdb-modal :show="loginErrors !=null" @close="$store.dispatch('auth/clearErrorMessage')">
            <mdb-modal-header  >
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
                <mdb-btn color="secondary" @click.native="clearError()">閉じる</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>
    </div>
  
  
</template>

<script>
import { mdbModal, mdbModalHeader, mdbModalTitle, mdbModalBody, mdbModalFooter, mdbBtn,  } from 'mdbvue';
export default {
    components: {
      mdbModal,
      mdbModalHeader,
      mdbModalTitle,
      mdbModalBody,
      mdbModalFooter,
      mdbBtn, 
      
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
                this.$router.push('/timelines')
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

<style>
    .login-title{
        font-family: 'Julius Sans One', sans-serif;
    }
</style>