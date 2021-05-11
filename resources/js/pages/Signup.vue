<template>
  <div>
    <!-- Default form login -->
    <form @submit.prevent="signup">
      <h2 class="h4 text-center mb-4 mt-5">ユーザー登録</h2>
 
      <mdb-input label="ユーザー名" v-model="registerForm.name" type="text" id="signup-name" class="" />

      <mdb-input label="メールアドレス" v-model="registerForm.email" type="email" id="signup-email" class="" />

      <mdb-input label="パスワード" v-model="registerForm.password" type="password" id="signup-pass" class=""/>
  
      <mdb-input label="パスワード再入力" v-model="registerForm.password_confirmation" type="password" id="signup-confirmation" class=""/>
      <div class="text-center mt-4">
        <button class="btn btn-indigo shadow-none" type="submit">登録</button>
      </div>
    </form>
    <!-- Default form login -->
    <mdb-modal :show="registerErrors !=null" @close="$store.dispatch('auth/clearErrorMessage')">
        <mdb-modal-header  >
            <mdb-modal-title>警告</mdb-modal-title>
        </mdb-modal-header>
        <mdb-modal-body>
            <div v-if="registerErrors != null" class="errors">
                <ul v-if="registerErrors.name">
                <li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li>
              </ul>
              <ul v-if="registerErrors.email">
                <li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
              </ul>
              <ul v-if="registerErrors.password">
                <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
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
import { mdbModal, mdbModalHeader, mdbModalTitle, mdbModalBody, mdbModalFooter, mdbBtn, mdbInput } from 'mdbvue';
export default {
    data(){
        return{
            registerForm:{
                name:"",
                email:"",
                password:"",
                password_confirmation:""
            }
        }
    },
    components: {
      mdbModal,
      mdbModalHeader,
      mdbModalTitle,
      mdbModalBody,
      mdbModalFooter,
      mdbBtn,
      mdbInput,
    },
    methods:{
      // ユーザー登録
        async signup(){
            // authストアのresigterアクションを呼び出す
            await this.$store.dispatch('auth/register', this.registerForm)
              if (this.apiStatus) {
              // トップページに移動する
              this.$router.push({ name:'waitVerify' })
          }
        },
        // エラーを消す
        clearError () {
        this.$store.commit('auth/setRegisterErrorMessages', null)
      }
    },
    computed:{
        apiStatus () {
            return this.$store.state.auth.apiStatus
        },
        registerErrors () {
            return this.$store.state.auth.registerErrorMessages
        },
        
    },
    created () {
        this.clearError()
    }

}
</script>